<?php

namespace App\Data\Expense\Budget\Form;

use App\Data\Expense\Budget\ExpenseBudgetResource;
use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseBudgetFormProps extends Resource
{
    public function __construct(
        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseCategoryResource::class)]
        public Lazy|DataCollection $expenseCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseSubCategoryResource::class)]
        public Lazy|DataCollection $expenseSubCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseItemResource::class)]
        public Lazy|DataCollection $expenseItems,

        public ?ExpenseBudgetResource $expenseBudget,
    ) {}
}
