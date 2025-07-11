<?php

namespace App\Data\Deal\Commercial\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Client\ClientResource;
use App\Data\Deal\DealResource;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealIndexProps extends Resource
{
    public function __construct(
        public CommercialDealIndexRequest $request,

        public array $accounting_period_months,

        #[AutoInertiaLazy]
        #[DataCollectionOf(DealResource::class)]
        public Lazy|PaginatedDataCollection $commercial_deals,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashed_filters,

        #[AutoInertiaLazy]
        #[DataCollectionOf(AccountingPeriodResource::class)]
        public Lazy|DataCollection $accountingPeriods,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientResource::class)]
        public Lazy|DataCollection $clients,

    ) {}
}
