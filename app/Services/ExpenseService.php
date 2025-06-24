<?php

namespace App\Services;

use App\Actions\Expense\Category\CreateOrUpdateExpenseCategory;
use App\Actions\Expense\Item\CreateOrUpdateExpenseItem;
use App\Actions\Expense\SubCategory\CreateOrUpdateExpenseSubCategory;
use App\Data\Expense\Category\ExpenseCategoryListResource;
use App\Data\Expense\Item\ExpenseItemListResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryListResource;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
use App\Models\ExpenseSubCategory;
use Closure;

class ExpenseService
{
    public function __construct(
        public CreateOrUpdateExpenseCategory $createOrUpdateCategory,
        public CreateOrUpdateExpenseItem $createOrUpdateItem,
        public CreateOrUpdateExpenseSubCategory $createOrUpdateSubCategory,
    ) {}

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<ExpenseCategory> $query): Builder)  $callback
     */
    public function categoriesList(?Closure $callback = null)
    {
        return ExpenseCategoryListResource::collect(
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
        return ExpenseSubCategoryListResource::collect(
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
        return ExpenseItemListResource::collect(
            value($callback ?? ExpenseItem::query(), ExpenseItem::query())
                ->orderBy('name')
                ->get(),
        );
    }
}
