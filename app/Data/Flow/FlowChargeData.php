<?php

namespace App\Data\Flow;

use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class FlowChargeData extends Data
{
    public function __construct(
        public ?int $category_id,
        public ?string $category_name,
        public string $date,
        public float $amount,
    ) {}
}
