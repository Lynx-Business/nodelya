<?php

namespace App\Data\Employee\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Employee\EmployeeResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EmployeeIndexProps extends Resource
{
    public function __construct(
        public EmployeeIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(EmployeeResource::class)]
        public Lazy|PaginatedDataCollection $employees,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashedFilters,

        #[AutoInertiaLazy]
        #[DataCollectionOf(AccountingPeriodResource::class)]
        public Lazy|DataCollection $accountingPeriods,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,
    ) {}
}
