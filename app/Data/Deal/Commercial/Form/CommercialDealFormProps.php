<?php

namespace App\Data\Deal\Commercial\Form;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealFormProps extends Resource
{
    public function __construct(
        public ?CommercialDealFormResource $deal,
    ) {}
}
