<?php

namespace App\Traits;

use App\Models\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToExpenseCategory
{
    public static function bootBelongsToExpenseCategory(): void {}

    public function initializeBelongsToExpenseCategory(): void {}

    public function getExpenseCategoryIdColumn(): string
    {
        return defined(static::class.'::EXPENSE_CATEGORY_ID') ? static::EXPENSE_CATEGORY_ID : 'expense_category_id';
    }

    public function getQualifiedExpenseCategoryIdColumn(): string
    {
        return $this->qualifyColumn($this->getExpenseCategoryIdColumn());
    }

    public function expenseCategory(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, $this->getExpenseCategoryIdColumn())->withTrashed();
    }

    public function scopeWhereBelongsToExpenseCategory(Builder $query, ExpenseCategory|int $expenseCategory): Builder
    {
        $expenseCategory = is_int($expenseCategory) ? $expenseCategory : $expenseCategory->getKey();

        return $query
            ->where($this->getQualifiedExpenseCategoryIdColumn(), $expenseCategory);
    }
}
