<?php

namespace App\Models;

use App\Enums\Expense\ExpenseType;
use App\Traits\BelongsToTeam;
use App\Traits\HasPolicy;
use App\Traits\Searchable;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $team_id
 * @property ExpenseType $type
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read bool $can_delete
 * @property-read bool $can_restore
 * @property-read bool $can_trash
 * @property-read bool $can_update
 * @property-read bool $can_view
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseItem> $expenseItems
 * @property-read int $expense_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseSubCategory> $expenseSubCategories
 * @property-read int $expense_sub_categories_count
 * @property-read true $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ExpenseCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory search(?string $q)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpenseCategory extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseCategoryFactory> */
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
        'type',
        'name',
    ];

    /**
     * The attributes that are searchable.
     *
     * @var list<string>
     */
    protected $searchable = [
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
            'type' => ExpenseType::class,
        ];
    }

    public function expenseSubCategories(): HasMany
    {
        return $this->hasMany(ExpenseSubCategory::class);
    }

    public function expenseItems(): HasMany
    {
        return $this->hasMany(ExpenseItem::class);
    }

    public function expenseSubCategoriesCount(): Attribute
    {
        return Attribute::get(fn (?int $value): int => $value ?? $this->expenseSubCategories()->count());
    }

    public function expenseItemsCount(): Attribute
    {
        return Attribute::get(fn (?int $value): int => $value ?? $this->expenseItems()->count());
    }
}
