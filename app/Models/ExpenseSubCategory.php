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
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $team_id
 * @property int $expense_category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \App\Models\ExpenseCategory $expenseCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseItem> $expenseItems
 * @property-read int|null $expense_items_count
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\Team $team
 * @property-read ExpenseType $type
 *
 * @method static \Database\Factories\ExpenseSubCategoryFactory factory($count = null, $state = [])
 * @method static Builder<static>|ExpenseSubCategory filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static Builder<static>|ExpenseSubCategory newModelQuery()
 * @method static Builder<static>|ExpenseSubCategory newQuery()
 * @method static Builder<static>|ExpenseSubCategory onlyTrashed()
 * @method static Builder<static>|ExpenseSubCategory query()
 * @method static Builder<static>|ExpenseSubCategory search(?string $q)
 * @method static Builder<static>|ExpenseSubCategory whereBelongsToExpenseCategory(\App\Models\ExpenseCategory|int $expenseCategory)
 * @method static Builder<static>|ExpenseSubCategory whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static Builder<static>|ExpenseSubCategory whereCreatedAt($value)
 * @method static Builder<static>|ExpenseSubCategory whereDeletedAt($value)
 * @method static Builder<static>|ExpenseSubCategory whereExpenseCategoryId($value)
 * @method static Builder<static>|ExpenseSubCategory whereId($value)
 * @method static Builder<static>|ExpenseSubCategory whereName($value)
 * @method static Builder<static>|ExpenseSubCategory whereTeamId($value)
 * @method static Builder<static>|ExpenseSubCategory whereType(\App\Enums\Expense\ExpenseType $type)
 * @method static Builder<static>|ExpenseSubCategory whereUpdatedAt($value)
 * @method static Builder<static>|ExpenseSubCategory withTrashed()
 * @method static Builder<static>|ExpenseSubCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpenseSubCategory extends Model
{
    use BelongsToExpenseCategory;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseSubCategoryFactory> */
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
        'name',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
        'expenseCategory.name',
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

    public function expenseItems(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
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
