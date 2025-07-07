<?php

namespace App\Data\Deal\Billing\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Client\ClientListResource;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BillingDealIndexProps extends Resource
{
    public function __construct(
        public BillingDealIndexRequest $request,

        // TODO add structure
        public array $accounting_period_months,

        #[AutoInertiaLazy]
        #[DataCollectionOf(BillingDealIndexResource::class)]
        public Lazy|PaginatedDataCollection $billing_deals,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashed_filters,

        #[AutoInertiaLazy]
        #[DataCollectionOf(AccountingPeriodResource::class)]
        public Lazy|DataCollection $accountingPeriods,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientListResource::class)]
        public Lazy|DataCollection $clients,
    ) {}
}
