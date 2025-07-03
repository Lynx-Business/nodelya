<?php

namespace App\Data\Employee\Expense\Charge\Form;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Employee\EmployeeResource;
use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Charge\ExpenseChargeResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EmployeeExpenseChargeFormProps extends Resource
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

        public ?ExpenseChargeResource $expenseCharge,
    ) {
        $this->expenseCharge?->include('can_update');
    }
}
