<?php

namespace App\Data\Deal\Billing\Form;

use App\Data\Client\ClientListResource;
use App\Data\Deal\DealListResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BillingDealFormProps extends Resource
{
    public function __construct(
        public ?BillingDealFormResource $deal,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientListResource::class)]
        public Lazy|DataCollection $clients,

        #[AutoInertiaLazy]
        #[DataCollectionOf(DealListResource::class)]
        public Lazy|DataCollection $deals,
    ) {}
}
