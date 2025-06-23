<?php

namespace App\Data\Deal\Commercial\Index;

use App\Attributes\EnumArrayOf;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealIndexProps extends Resource
{
    public function __construct(
        public CommercialDealIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(CommercialDealIndexResource::class)]
        public Lazy|PaginatedDataCollection $commercial_deals,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashed_filters,
    ) {}
}
