<?php

namespace App\Data\Deal\Commercial\Validate;

use App\Models\Deal;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealValidateProps extends Resource
{
    public function __construct(

        public Deal $deal,

        public string $reference,
    ) {}
}
