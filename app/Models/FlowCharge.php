<?php

namespace App\Models;

use App\Facades\Services;
use App\Traits\BelongsToFlowCategory;
use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $team_id
 * @property int $flow_category_id
 * @property int $amount_in_cents
 * @property \Illuminate\Support\Carbon $charged_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $amount
 * @property-read \App\Models\FlowCategory $flowCategory
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\FlowChargeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereAmountInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereBelongsToFlowCategory(\App\Models\FlowCategory|int $flowCategory)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereChargedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereFlowCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlowCharge whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FlowCharge extends Model
{
    use BelongsToFlowCategory;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\FlowChargeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'flow_category_id',
        'amount_in_cents',
        'charged_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'charged_at' => 'date',
        ];
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): float => Services::conversion()->centsToPrice(data_get($attributes, 'amount_in_cents')),
            set: fn (?float $value) => ['amount_in_cents' => Services::conversion()->priceToCents($value)],
        );
    }
}
