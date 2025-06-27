<?php

namespace App\Models;

use App\Data\Deal\YearScheduleData;
use App\Enums\Deal\DealStatus;
use App\Facades\Services;
use App\Traits\BelongsToClient;
use App\Traits\BelongsToProjectDepartment;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\DataCollection;

/**
 * @property int $id
 * @property int $team_id
 * @property int|null $project_department_id
 * @property int $client_id
 * @property int|null $deal_id
 * @property string $name
 * @property DealStatus $status
 * @property int $amount_in_cents
 * @property string $code
 * @property string|null $reference
 * @property int $success_rate
 * @property \Illuminate\Support\Carbon $ordered_at
 * @property int $duration_in_months
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon|null $ends_at
 * @property \Spatie\LaravelData\DataCollection $schedule
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property float $amount
 * @property-read \App\Models\User $client
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\ProjectDepartment|null $projectDepartment
 * @property-read mixed $revenue
 * @property-read \App\Models\Team $team
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal commercial()
 * @method static \Database\Factories\DealFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal search(?string $q)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereAmountInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereBelongsToClient(\App\Models\Client|int $client)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereBelongsToProjectDepartment(\App\Models\ProjectDepartment|int $projectDepartment)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereDealId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereDurationInMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereOrderedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereProjectDepartmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereSuccessRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Deal extends Model
{
    use BelongsToClient;
    use BelongsToProjectDepartment;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\DealFactory> */
    use HasFactory;

    use HasPolicy;
    use Searchable;
    use Trashable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'project_department_id',
        'client_id',
        'name',
        'status',
        'amount_in_cents',
        'code',
        'reference',
        'success_rate',
        'ordered_at',
        'duration_in_months',
        'starts_at',
        'schedule',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status'     => DealStatus::class,
            'ordered_at' => 'date',
            'starts_at'  => 'date',
            'ends_at'    => 'date',
            'schedule'   => DataCollection::class.':'.YearScheduleData::class.',nullable',

        ];
    }

    public static function booted(): void
    {
        static::creating(function (Deal $deal) {
            $deal->status ??= DealStatus::CREATED;
        });
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): float => Services::conversion()->centsToPrice(data_get($attributes, 'amount_in_cents')),
            set: fn (?float $value) => ['amount_in_cents' => Services::conversion()->priceToCents($value)],
        );
    }

    protected function revenue(): Attribute
    {
        return Attribute::get(
            function () {
                if ($this->status == DealStatus::VALIDATED) {
                    return $this->amount;
                }

                return Services::conversion()->centsToPrice($this->amount_in_cents * ($this->success_rate / 100));
            });
    }

    public function scopeCommercial($query)
    {
        return $query->where('status', DealStatus::CREATED);
    }
}
