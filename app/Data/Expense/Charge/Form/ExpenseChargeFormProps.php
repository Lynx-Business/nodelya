<?php

namespace App\Data\Expense\Charge\Form;

use App\Data\AccountingPeriod\AccountingPeriodResource;
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
class ExpenseChargeFormProps extends Resource
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

        public AccountingPeriodResource $accountingPeriod,

        public ?ExpenseChargeResource $expenseCharge,
    ) {
        $this->accountingPeriod->include('months');
        $this->expenseCharge?->include('can_update');
    }
}
