<?php

namespace App\Data\Treasury;

use Carbon\Carbon;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class TreasuryIndexRequest extends Data
{
    public function __construct(
        public ?Carbon $starts_at,

        public ?Carbon $ends_at,
    ) {
        $this->starts_at ??= now()->startOfMonth();
        $this->ends_at ??= $this->starts_at->copy()->addMonths(11)->endOfMonth();
    }

    public static function attributes(): array
    {
        return [
            'starts_at' => __('starts_at'),
            'ends_at'   => __('ends_at'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            //
        ];
    }
}
