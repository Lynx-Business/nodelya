<?php

namespace App\Data\Deal\Billing\Index;

use App\Attributes\EnumArrayOf;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BillingDealIndexProps extends Resource
{
    public function __construct(
        public BillingDealIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(BillingDealIndexResource::class)]
        public Lazy|PaginatedDataCollection $billing_deals,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashed_filters,
    ) {}
}
