<?php

namespace App\Data\Treasury;

use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TreasuryIndexProps extends Resource
{
    public function __construct(
        public TreasuryIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(TreasuryMonthResource::class)]
        public Lazy|DataCollection $months,
    ) {}
}
