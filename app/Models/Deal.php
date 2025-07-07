<?php

namespace App\Models;

use App\Data\Deal\DealScheduleData;
use App\Data\Deal\MonthlyExpenseData;
use App\Enums\Deal\DealScheduleStatus;
use App\Enums\Deal\DealStatus;
use App\Facades\Services;
use App\Traits\BelongsToClient;
use App\Traits\BelongsToProjectDepartment;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
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
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \App\Models\Client $client
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read Deal|null $parent
 * @property-read \App\Models\ProjectDepartment|null $projectDepartment
 * @property-read mixed $revenue
 * @property-read \App\Models\Team $team
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deal billing()
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
        'deal_id',
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
        'amount',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var array
     */
    protected $searchable = [
        'name',
        'code',
        'reference',
        'client.name',
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
            'schedule'   => DataCollection::class.':'.DealScheduleData::class,
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function parent()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
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

    public function scopeBilling($query)
    {
        return $query->where('status', DealStatus::VALIDATED);
    }

    protected function schedule(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => DealScheduleData::collect(json_decode($value)),
        );
    }

    public function scopeWhereInAccountingPeriod(Builder $query, AccountingPeriod|int $accountingPeriod): Builder
    {
        $accountingPeriodModel = app(AccountingPeriod::class);
        $id = is_int($accountingPeriod) ? $accountingPeriod : $accountingPeriod->getKey();

        return $query->whereNot->whereExists(
            fn (QueryBuilder $q) => $q
                ->select(DB::raw(1))
                ->from($accountingPeriodModel->getTable())
                ->where($accountingPeriodModel->getQualifiedKeyName(), $id)
                ->where(
                    fn (QueryBuilder $q) => $q
                        ->whereColumn($accountingPeriodModel->qualifyColumn('starts_at'), '>=', $this->qualifyColumn('ends_at'))
                        ->orWhereColumn($accountingPeriodModel->qualifyColumn('ends_at'), '<=', $this->qualifyColumn('starts_at')),
                ),
        );
    }

    protected function monthlyStatus(): Attribute
    {
        return Attribute::get(function () {
            $schedule = $this->schedule;
            if (empty($schedule)) {
                return [];
            }

            $statusPriority = [
                DealScheduleStatus::UNCERTAIN->value         => 4,
                DealScheduleStatus::PENDING_INVOICING->value => 3,
                DealScheduleStatus::INVOICED->value          => 2,
                DealScheduleStatus::PAID->value              => 1,
            ];

            $monthlyStatus = [];
            foreach ($schedule as $yearSchedule) {
                foreach ($yearSchedule->data as $item) {
                    $month = $item->date->format('Y-m');
                    $itemStatus = $item->status->value ?? $item->status;
                    $currentPriority = $statusPriority[$monthlyStatus[$month] ?? null] ?? 0;
                    $itemPriority = $statusPriority[$itemStatus] ?? 0;

                    if ($itemPriority > $currentPriority) {
                        $monthlyStatus[$month] = $itemStatus;
                    }
                }
            }

            return $monthlyStatus;
        });
    }

    protected function monthlyExpenses(): Attribute
    {
        return Attribute::get(function () {
            $monthlyAmounts = [];
            $monthlyRawDates = [];
            $monthlyStatuses = $this->monthly_status;

            if ($this->schedule) {
                foreach ($this->schedule as $yearSchedule) {
                    foreach ($yearSchedule->data as $item) {
                        $month = $item->date->format('Y-m');
                        $monthlyAmounts[$month] = ($monthlyAmounts[$month] ?? 0) + $item->amount;
                        $monthlyRawDates[$month] = $item->date;
                    }
                }
            }

            $result = [];
            foreach ($monthlyAmounts as $month => $amount) {
                $result[$month] = new MonthlyExpenseData(
                    date_key: $month,
                    amount: $amount,
                    status: $monthlyStatuses[$month],
                    date: $monthlyRawDates[$month],
                );
            }

            return $result;
        });
    }
}
