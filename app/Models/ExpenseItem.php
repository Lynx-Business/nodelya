<?php

namespace App\Models;

use App\Enums\Expense\ExpenseType;
use App\Traits\BelongsToExpenseCategory;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $team_id
 * @property int $expense_category_id
 * @property int|null $expense_sub_category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseBudget> $expenseBudgets
 * @property-read int|null $expense_budgets_count
 * @property-read \App\Models\ExpenseCategory $expenseCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseCharge> $expenseCharges
 * @property-read int|null $expense_charges_count
 * @property-read \App\Models\ExpenseSubCategory|null $expenseSubCategory
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\Team $team
 * @property-read ExpenseType $type
 *
 * @method static \Database\Factories\ExpenseItemFactory factory($count = null, $state = [])
 * @method static Builder<static>|ExpenseItem filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static Builder<static>|ExpenseItem newModelQuery()
 * @method static Builder<static>|ExpenseItem newQuery()
 * @method static Builder<static>|ExpenseItem onlyTrashed()
 * @method static Builder<static>|ExpenseItem query()
 * @method static Builder<static>|ExpenseItem search(?string $q)
 * @method static Builder<static>|ExpenseItem whereBelongsToExpenseCategory(\App\Models\ExpenseCategory|int $expenseCategory)
 * @method static Builder<static>|ExpenseItem whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static Builder<static>|ExpenseItem whereCreatedAt($value)
 * @method static Builder<static>|ExpenseItem whereDeletedAt($value)
 * @method static Builder<static>|ExpenseItem whereExpenseCategoryId($value)
 * @method static Builder<static>|ExpenseItem whereExpenseSubCategoryId($value)
 * @method static Builder<static>|ExpenseItem whereId($value)
 * @method static Builder<static>|ExpenseItem whereName($value)
 * @method static Builder<static>|ExpenseItem whereTeamId($value)
 * @method static Builder<static>|ExpenseItem whereType(\App\Enums\Expense\ExpenseType $type)
 * @method static Builder<static>|ExpenseItem whereUpdatedAt($value)
 * @method static Builder<static>|ExpenseItem withTrashed()
 * @method static Builder<static>|ExpenseItem withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpenseItem extends Model
{
    use BelongsToExpenseCategory;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseItemFactory> */
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
        'expense_category_id',
        'expense_sub_category_id',
        'name',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
        'expenseCategory.name',
        'expenseSubCategory.name',
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

    public function expenseBudgets(): HasMany
    {
        return $this->hasMany(ExpenseBudget::class);
    }

    public function expenseCharges(): HasMany
    {
        return $this->hasMany(ExpenseCharge::class);
    }

    public function expenseSubCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseSubCategory::class);
    }

    protected function type(): Attribute
    {
        return Attribute::get(fn (): ExpenseType => $this->expenseCategory->type);
    }

    public function scopeWhereType(Builder $query, ExpenseType $type): Builder
    {
        return $query->whereRelation('expenseCategory', 'type', $type);
    }
}
