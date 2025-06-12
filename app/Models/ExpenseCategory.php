<?php

namespace App\Models;

use App\Enums\Expense\Category\ExpenseCategoryType;
use App\Traits\BelongsToTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $team_id
 * @property ExpenseCategoryType $type
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseItem> $expenseItems
 * @property-read int|null $expense_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ExpenseSubCategory> $expenseSubCategories
 * @property-read int|null $expense_sub_categories_count
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ExpenseCategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseCategory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class ExpenseCategory extends Model
{
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseCategoryFactory> */
    use HasFactory;

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => ExpenseCategoryType::class,
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
}
