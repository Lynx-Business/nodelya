<?php

namespace App\Models;

use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Traits\BelongsToAccountingPeriod;
use App\Traits\BelongsToExpenseItem;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property int $id
 * @property int $team_id
 * @property int $expense_item_id
 * @property int $accounting_period_id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int $amount_in_cents
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\AccountingPeriod $accountingPeriod
 * @property float $amount
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read int $duration_in_months
 * @property-read Carbon $ends_at
 * @property-read \App\Models\ExpenseItem $expenseItem
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read Model|\Eloquent|null $model
 * @property-read float $monthly_amount
 * @property-read int $monthly_amount_in_cents
 * @property-read Carbon $starts_at
 * @property-read \App\Models\Team $team
 * @property-read ExpenseType $type
 *
 * @method static \Database\Factories\ExpenseBudgetFactory factory($count = null, $state = [])
 * @method static Builder<static>|ExpenseBudget filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static Builder<static>|ExpenseBudget newModelQuery()
 * @method static Builder<static>|ExpenseBudget newQuery()
 * @method static Builder<static>|ExpenseBudget onlyTrashed()
 * @method static Builder<static>|ExpenseBudget query()
 * @method static Builder<static>|ExpenseBudget search(?string $q)
 * @method static Builder<static>|ExpenseBudget whereAccountingPeriodId($value)
 * @method static Builder<static>|ExpenseBudget whereAmountInCents($value)
 * @method static Builder<static>|ExpenseBudget whereBelongsToAccountingPeriod(\App\Models\AccountingPeriod|int $accountingPeriod)
 * @method static Builder<static>|ExpenseBudget whereBelongsToExpenseItem(\App\Models\ExpenseItem|int $expenseItem)
 * @method static Builder<static>|ExpenseBudget whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static Builder<static>|ExpenseBudget whereCreatedAt($value)
 * @method static Builder<static>|ExpenseBudget whereDeletedAt($value)
 * @method static Builder<static>|ExpenseBudget whereExpenseItemId($value)
 * @method static Builder<static>|ExpenseBudget whereId($value)
 * @method static Builder<static>|ExpenseBudget whereModelId($value)
 * @method static Builder<static>|ExpenseBudget whereModelType($value)
 * @method static Builder<static>|ExpenseBudget whereTeamId($value)
 * @method static Builder<static>|ExpenseBudget whereType(\App\Enums\Expense\ExpenseType $type)
 * @method static Builder<static>|ExpenseBudget whereUpdatedAt($value)
 * @method static Builder<static>|ExpenseBudget withTrashed()
 * @method static Builder<static>|ExpenseBudget withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpenseBudget extends Model
{
    use BelongsToAccountingPeriod;
    use BelongsToExpenseItem;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseBudgetFactory> */
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
        'accounting_period_id',
        'expense_item_id',
        'model_type',
        'model_id',
        'amount_in_cents',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
        'expenseItem.expenseCategory.name',
        'expenseItem.expenseSubCategory.name',
        'expenseItem.name',
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

    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    protected function startsAt(): Attribute
    {
        return Attribute::get(fn (): Carbon => $this->accountingPeriod->starts_at);
    }

    protected function endsAt(): Attribute
    {
        return Attribute::get(fn (): Carbon => match ($this->model_type) {
            ExpenseType::EMPLOYEE => $this->model?->ends_at ?? $this->accountingPeriod->ends_at,
            default               => $this->accountingPeriod->ends_at,
        });
    }

    protected function durationInMonths(): Attribute
    {
        return Attribute::get(
            fn (): int => ceil($this->starts_at->diffInMonths($this->ends_at, absolute: true)),
        );
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes): float => Services::conversion()->centsToPrice(data_get($attributes, 'amount_in_cents', default: 0)),
            set: fn (?float $value) => ['amount_in_cents' => Services::conversion()->priceToCents($value)],
        );
    }

    protected function monthlyAmountInCents(): Attribute
    {
        return Attribute::get(
            fn (): int => $this->duration_in_months > 0 ? round($this->amount_in_cents / $this->duration_in_months) : 0,
        );
    }

    protected function monthlyAmount(): Attribute
    {
        return Attribute::get(
            fn (): float => Services::conversion()->centsToPrice($this->monthly_amount_in_cents) ?? 0,
        );
    }

    protected function type(): Attribute
    {
        return Attribute::get(fn (): ExpenseType => ExpenseType::fromMorphType($this->model_type));
    }

    public function scopeWhereType(Builder $query, ExpenseType $type): Builder
    {
        return $query->where(
            'model_type',
            match ($type) {
                ExpenseType::CONTRACTOR => Relation::getMorphAlias(Contractor::class),
                ExpenseType::EMPLOYEE   => Relation::getMorphAlias(Employee::class),
                ExpenseType::GENERAL    => null,
                default                 => null,
            },
        );
    }
}
