<?php

namespace App\Data\Contractor\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Contractor\ContractorResource;
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
class ContractorIndexProps extends Resource
{
    public function __construct(
        public ContractorIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ContractorResource::class)]
        public Lazy|PaginatedDataCollection $contractors,

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
