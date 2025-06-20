<?php

namespace App\Models;

use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FlowCharge> $flowCharges
 * @property-read int|null $flow_charges_count
 * @property-read \App\Models\Team|null $team
 *
 * @method static \Database\Factories\FlowCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FlowCategory extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\FlowCategoryFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'name',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            //
        ];
    }

    public function flowCharges(): HasMany
    {
        return $this->hasMany(FlowCharge::class);
    }
}
