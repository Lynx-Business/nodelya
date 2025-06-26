<?php

namespace App\Data\Deal\Commercial\Form;

use App\Data\Client\ClientListResource;
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
        public ?CommercialDealFormResource $deal,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientListResource::class)]
        public Lazy|DataCollection $clients,
    ) {}
}
