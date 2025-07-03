<?php

namespace App\Models;

use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Traits\BelongsToExpenseItem;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\IsInAccountingPeriod;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property int $team_id
 * @property int $expense_item_id
 * @property string|null $model_type
 * @property int|null $model_id
 * @property int $amount_in_cents
 * @property \Illuminate\Support\Carbon $starts_at
 * @property \Illuminate\Support\Carbon $ends_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property float $amount
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \App\Models\ExpenseItem $expenseItem
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read Model|\Eloquent|null $model
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
 * @method static Builder<static>|ExpenseBudget whereAmountInCents($value)
 * @method static Builder<static>|ExpenseBudget whereBelongsToExpenseItem(\App\Models\ExpenseItem|int $expenseItem)
 * @method static Builder<static>|ExpenseBudget whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static Builder<static>|ExpenseBudget whereCreatedAt($value)
 * @method static Builder<static>|ExpenseBudget whereDeletedAt($value)
 * @method static Builder<static>|ExpenseBudget whereEndsAt($value)
 * @method static Builder<static>|ExpenseBudget whereExpenseItemId($value)
 * @method static Builder<static>|ExpenseBudget whereId($value)
 * @method static Builder<static>|ExpenseBudget whereInAccountingPeriod(\App\Models\AccountingPeriod|int $accountingPeriod)
 * @method static Builder<static>|ExpenseBudget whereModelId($value)
 * @method static Builder<static>|ExpenseBudget whereModelType($value)
 * @method static Builder<static>|ExpenseBudget whereStartsAt($value)
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
    use BelongsToExpenseItem;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseBudgetFactory> */
    use HasFactory;

    use HasPolicy;
    use IsInAccountingPeriod;
    use Searchable;
    use Trashable;

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
            'starts_at' => 'datetime',
            'ends_at'   => 'datetime',
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

    public function isInAccountingPeriod(AccountingPeriod|int $accountingPeriod): bool
    {
        $accountingPeriod = is_int($accountingPeriod) ? AccountingPeriod::query()->findOrFail($accountingPeriod) : $accountingPeriod;

        return $this->starts_at >= $accountingPeriod->starts_at && $this->ends_at <= $accountingPeriod->ends_at;
    }

    public function scopeWhereInAccountingPeriod(Builder $query, AccountingPeriod|int $accountingPeriod): Builder
    {
        $accountingPeriodModel = app(AccountingPeriod::class);
        $id = is_int($accountingPeriod) ? $accountingPeriod : $accountingPeriod->getKey();

        return $query->whereExists(
            fn (QueryBuilder $q) => $q
                ->select(DB::raw(1))
                ->from($accountingPeriodModel->getTable())
                ->where($accountingPeriodModel->getQualifiedKeyName(), $id)
                ->whereColumn($accountingPeriodModel->qualifyColumn('starts_at'), '<=', $this->qualifyColumn('starts_at'))
                ->whereColumn($accountingPeriodModel->qualifyColumn('ends_at'), '>=', $this->qualifyColumn('ends_at')),
        );
    }
}
