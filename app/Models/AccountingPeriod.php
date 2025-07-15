<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $team_id
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read int $duration_in_months
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read string $label
 * @property-read Collection $months
 * @property-read \App\Models\Team $team
 *
 * @method static Builder<static>|AccountingPeriod current()
 * @method static \Database\Factories\AccountingPeriodFactory factory($count = null, $state = [])
 * @method static Builder<static>|AccountingPeriod filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static Builder<static>|AccountingPeriod newModelQuery()
 * @method static Builder<static>|AccountingPeriod newQuery()
 * @method static Builder<static>|AccountingPeriod onlyTrashed()
 * @method static Builder<static>|AccountingPeriod query()
 * @method static Builder<static>|AccountingPeriod search(?string $q)
 * @method static Builder<static>|AccountingPeriod whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static Builder<static>|AccountingPeriod whereCreatedAt($value)
 * @method static Builder<static>|AccountingPeriod whereDeletedAt($value)
 * @method static Builder<static>|AccountingPeriod whereEndsAt($value)
 * @method static Builder<static>|AccountingPeriod whereId($value)
 * @method static Builder<static>|AccountingPeriod whereStartsAt($value)
 * @method static Builder<static>|AccountingPeriod whereTeamId($value)
 * @method static Builder<static>|AccountingPeriod whereUpdatedAt($value)
 * @method static Builder<static>|AccountingPeriod withTrashed()
 * @method static Builder<static>|AccountingPeriod withoutTrashed()
 *
 * @mixin \Eloquent
 */
class AccountingPeriod extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\AccountingPeriodFactory> */
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
        'starts_at',
        'ends_at',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
        'starts_at',
        'ends_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
        ];
    }

    protected function label(): Attribute
    {
        return Attribute::get(
            fn (): string => $this->starts_at->isSameYear($this->ends_at)
                ? "{$this->starts_at->year}"
                : "{$this->starts_at->year}-{$this->ends_at->year}",
        );
    }

    protected function durationInMonths(): Attribute
    {
        return Attribute::get(
            fn (): int => ceil($this->starts_at->diffInMonths($this->ends_at, absolute: true)),
        );
    }

    protected function months(): Attribute
    {
        return Attribute::get(
            fn (): Collection => collect(range(0, $this->duration_in_months))
                ->map(fn (int $i) => $this->starts_at->copy()->startOfMonth()->addMonths($i))
                ->values(),
        );
    }

    public function scopeCurrent(Builder $query): Builder
    {
        return $query->where([
            ['starts_at', '<=', now()],
            ['ends_at', '>=', now()],
        ]);
    }
}
