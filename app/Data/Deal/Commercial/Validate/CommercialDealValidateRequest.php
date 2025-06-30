<?php

namespace App\Data\Deal\Commercial\Validate;

use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class CommercialDealValidateRequest extends Data
{
    public function __construct(

        public float $amount,

        public string $reference,

    ) {}

    public static function attributes(): array
    {
        return [
            'amount'    => __('models.commercial_deal.fields.amount'),
            'reference' => __('models.commercial_deal.fields.reference'),
        ];
    }
}
