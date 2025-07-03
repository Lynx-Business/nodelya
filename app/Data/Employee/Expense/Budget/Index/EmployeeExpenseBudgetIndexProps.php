<?php

namespace App\Data\Employee\Expense\Budget\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Employee\EmployeeResource;
use App\Data\Expense\Budget\ExpenseBudgetResource;
use App\Data\Expense\Budget\Index\ExpenseBudgetIndexRequest;
use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EmployeeExpenseBudgetIndexProps extends Resource
{
    public function __construct(
        public ExpenseBudgetIndexRequest $request,

        public EmployeeResource $employee,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseBudgetResource::class)]
        public Lazy|PaginatedDataCollection $expenseBudgets,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashedFilters,

        #[AutoInertiaLazy]
        #[DataCollectionOf(AccountingPeriodResource::class)]
        public Lazy|DataCollection $accountingPeriods,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseCategoryResource::class)]
        public Lazy|DataCollection $expenseCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseSubCategoryResource::class)]
        public Lazy|DataCollection $expenseSubCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseItemResource::class)]
        public Lazy|DataCollection $expenseItems,
    ) {}
}
