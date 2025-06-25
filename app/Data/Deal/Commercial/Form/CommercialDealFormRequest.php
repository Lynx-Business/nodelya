<?php

namespace App\Data\Deal\Commercial\Form;

use App\Models\Deal;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class CommercialDealFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('deal')]
        public ?Deal $deal,

        #[Max(255)]
        public string $name,

        public float $amount,

        public ?string $code,

        public ?string $reference,

        #[Min(0), Max(100)]
        public int $success_rate,

        public string $ordered_at,

        public int $duration_in_months,

        public string $starts_at,

        public ?array $schedule,

    ) {}

    public static function attributes(): array
    {
        return [
            'name' => __('models.deal.fields.name'),
        ];
    }
}
