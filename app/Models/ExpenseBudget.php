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
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $amount
 * @property-read \App\Models\ExpenseItem|null $expenseItem
 * @property-read Model|\Eloquent|null $model
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ExpenseBudgetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereAmountInCents($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereBelongsToExpenseItem(\App\Models\ExpenseItem|int $expenseItem)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseBudget whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ExpenseBudget extends Model
{
    use BelongsToExpenseItem;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseBudgetFactory> */
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
