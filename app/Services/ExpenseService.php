<?php

namespace App\Services;

use App\Actions\Expense\Budget\CreateOrUpdateExpenseBudget;
use App\Actions\Expense\Category\CreateOrUpdateExpenseCategory;
use App\Actions\Expense\Item\CreateOrUpdateExpenseItem;
use App\Actions\Expense\SubCategory\CreateOrUpdateExpenseSubCategory;
use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
use App\Models\ExpenseSubCategory;
use Closure;

class ExpenseService
{
    public function __construct(
        public CreateOrUpdateExpenseBudget $createOrUpdateBudget,
        public CreateOrUpdateExpenseCategory $createOrUpdateCategory,
        public CreateOrUpdateExpenseItem $createOrUpdateItem,
        public CreateOrUpdateExpenseSubCategory $createOrUpdateSubCategory,
    ) {}

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<ExpenseCategory> $query): Builder)  $callback
     */
    public function categoriesList(?Closure $callback = null)
    {
        return ExpenseCategoryResource::collect(
            value($callback ?? ExpenseCategory::query(), ExpenseCategory::query())
                ->orderBy('name')
                ->get(),
        );
    }

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<ExpenseSubCategory>  $query): Builder)  $callback
     */
    public function subCategoriesList(?Closure $callback = null)
    {
        return ExpenseSubCategoryResource::collect(
            value($callback ?? ExpenseSubCategory::query(), ExpenseSubCategory::query())
                ->with([
                    'expenseCategory',
                ])
                ->orderBy('name')
                ->get(),
        );
    }

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<ExpenseItem>  $query): Builder)  $callback
     */
    public function itemsList(?Closure $callback = null)
    {
        return ExpenseItemResource::collect(
            value($callback ?? ExpenseItem::query(), ExpenseItem::query())
                ->with([
                    'expenseCategory',
                ])
                ->orderBy('name')
                ->get(),
        );
    }
}
