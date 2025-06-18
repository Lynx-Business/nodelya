<?php

namespace App\Data\AccountingPeriod\Index;

use App\Attributes\EnumArrayOf;
use App\Data\Team\TeamListResource;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountingPeriodIndexProps extends Resource
{
    public function __construct(
        public AccountingPeriodIndexRequest $request,

        public TeamListResource $team,

        #[AutoInertiaLazy]
        #[DataCollectionOf(AccountingPeriodIndexResource::class)]
        public Lazy|PaginatedDataCollection $accountingPeriods,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashed_filters,
    ) {}
}
