<?php

namespace App\Data\Flow\Form;

use App\Data\Flow\FlowChargeData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class FlowFormRequest extends Data
{
    public function __construct(
        #[DataCollectionOf(FlowChargeData::class)]
        public array $charges,
    ) {}

    public static function attributes(): array
    {
        return [
            'charges' => __('models.flow.charge.list'),
        ];
    }
}
