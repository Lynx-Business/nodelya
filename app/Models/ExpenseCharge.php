<?php

namespace App\Models;

use App\Facades\Services;
use App\Traits\BelongsToExpenseItem;
use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $team_id
 * @property int $item_id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int $amount_in_cents
 * @property \Illuminate\Support\Carbon $charged_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $amount
 * @property-read \App\Models\ExpenseItem|null $expenseItem
 * @property-read Model|\Eloquent|null $model
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ExpenseChargeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereAmountInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereBelongsToExpenseItem(\App\Models\ExpenseItem|int $expenseItem)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereChargedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCharge whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ExpenseCharge extends Model
{
    use BelongsToExpenseItem;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseChargeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'team_id',
        'expense_item_id',
        'model_type',
        'model_id',
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

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): float => Services::conversion()->centsToPrice(data_get($attributes, 'amount_in_cents')),
            set: fn (?float $value) => ['amount_in_cents' => Services::conversion()->priceToCents($value)],
        );
    }
}
