<?php

namespace App\Models;

use App\Traits\BelongsToExpenseCategory;
use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $team_id
 * @property int $category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ExpenseCategory|null $expenseCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseItem> $expenseItems
 * @property-read int|null $expense_items_count
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ExpenseSubCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereBelongsToExpenseCategory(\App\Models\ExpenseCategory|int $expenseCategory)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseSubCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ExpenseSubCategory extends Model
{
    use BelongsToExpenseCategory;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseSubCategoryFactory> */
    use HasFactory;

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
}
