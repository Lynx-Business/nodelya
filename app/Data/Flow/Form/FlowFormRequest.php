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

    public static function rules(): array
    {
        return [
            'charges'          => ['required', 'array', 'min:1'],
            'charges.*.amount' => ['required', 'numeric', 'min:0'],
            'charges.*.date'   => ['required', 'date'],
            // On valide soit category_id, soit category_name
            'charges.*.category_id'   => ['nullable', 'integer', 'exists:flow_categories,id'],
            'charges.*.category_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
