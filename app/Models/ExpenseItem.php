<?php

namespace App\Models;

use App\Traits\BelongsToExpenseCategory;
use App\Traits\BelongsToTeam;
use App\Traits\Trashable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $team_id
 * @property int $category_id
 * @property int|null $sub_category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\ExpenseCategory|null $expenseCategory
 * @property-read \App\Models\ExpenseSubCategory|null $expenseSubCategory
 * @property-read mixed $is_trashable
 * @property bool $is_trashed
 * @property-read \App\Models\Team $team
 *
 * @method static \Database\Factories\ExpenseItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem filterTrashed(\App\Enums\Trashed\TrashedFilter $filter)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereBelongsToExpenseCategory(\App\Models\ExpenseCategory|int $expenseCategory)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereBelongsToTeam(\App\Models\Team|int $team)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereSubCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExpenseItem withoutTrashed()
 *
 * @mixin \Eloquent
 */
class ExpenseItem extends Model
{
    use BelongsToExpenseCategory;
    use BelongsToTeam;

    /** @use HasFactory<\Database\Factories\ExpenseItemFactory> */
    use HasFactory;

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

    public function expenseSubCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseSubCategory::class);
    }
}
