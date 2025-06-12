<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $team_id
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\AccountingPeriodFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AccountingPeriod whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AccountingPeriod extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\AccountingPeriodFactory> */
    use HasFactory;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'date',
            'ends_at'   => 'date',
        ];
    }
}
