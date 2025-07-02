<?php

namespace App\Traits;

use App\Models\ExpenseItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToExpenseItem
{
    public static function bootBelongsToExpenseItem(): void {}

    public function initializeBelongsToExpenseItem(): void {}

    public function getExpenseItemIdColumn(): string
    {
        return defined(static::class.'::EXPENSE_ITEM_ID') ? static::EXPENSE_ITEM_ID : 'expense_item_id';
    }

    public function getQualifiedExpenseItemIdColumn(): string
    {
        return $this->qualifyColumn($this->getExpenseItemIdColumn());
    }

    public function expenseItem(): BelongsTo
    {
        return $this->belongsTo(ExpenseItem::class, $this->getExpenseItemIdColumn())->withTrashed();
    }

    public function scopeWhereBelongsToExpenseItem(Builder $query, ExpenseItem|int $expenseItem): Builder
    {
        $expenseItem = is_int($expenseItem) ? $expenseItem : $expenseItem->getKey();

        return $query
            ->where($this->getQualifiedExpenseItemIdColumn(), $expenseItem);
    }
}
