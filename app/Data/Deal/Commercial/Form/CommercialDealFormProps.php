<?php

namespace App\Data\Deal\Commercial\Form;

use App\Data\Client\ClientResource;
use App\Data\Deal\DealResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealFormProps extends Resource
{
    public function __construct(
        public ?DealResource $deal,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientResource::class)]
        public Lazy|DataCollection $clients,

        #[AutoInertiaLazy]
        #[DataCollectionOf(DealResource::class)]
        public Lazy|DataCollection $deals,
    ) {}
}
