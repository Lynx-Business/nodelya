<?php

namespace App\Data\Employee\Expense\Budget\Form;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Employee\EmployeeResource;
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
class EmployeeExpenseBudgetFormProps extends Resource
{
    public function __construct(
        #[AutoInertiaLazy]
        public Lazy|AccountingPeriodResource $accountingPeriod,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseCategoryResource::class)]
        public Lazy|DataCollection $expenseCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseSubCategoryResource::class)]
        public Lazy|DataCollection $expenseSubCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseItemResource::class)]
        public Lazy|DataCollection $expenseItems,

        public EmployeeResource $employee,

        public ?ExpenseBudgetResource $expenseBudget,
    ) {
        $this->expenseBudget?->include('can_update');
    }
}
