<?php

namespace App\Data\Deal\Commercial\Validate;

use App\Data\Deal\DealListResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealValidateProps extends Resource
{
    public function __construct(

        public DealListResource $deal,

        public string $reference,
    ) {}
}
