<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read string $label
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\AccountingPeriodFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod search(?string $q)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod withoutTrashed()
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
}
