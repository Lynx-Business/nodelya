<?php

namespace App\Data\Expense\Management\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseManagementIndexProps extends Resource
{
    public function __construct(
        public ExpenseManagementIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(AccountingPeriodResource::class)]
        public Lazy|DataCollection $accountingPeriods,

        #[AutoInertiaLazy]
        #[EnumArrayOf(ExpenseType::class)]
        public Lazy|array $expenseTypes,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,

        #[AutoInertiaLazy]
        public Lazy|ExpenseManagementTypeResource $generalRow,

        #[AutoInertiaLazy]
        public Lazy|ExpenseManagementTypeResource $employeeRow,

        #[AutoInertiaLazy]
        public Lazy|ExpenseManagementTypeResource $contractorRow,
    ) {}
}
