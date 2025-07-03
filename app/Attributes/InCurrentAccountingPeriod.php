<?php

namespace App\Attributes;

use App\Facades\Services;
use Attribute;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\CustomValidationAttribute;
use Spatie\LaravelData\Support\Validation\ValidationPath;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class InCurrentAccountingPeriod extends CustomValidationAttribute
{
    /**
     * @return array<object|string>|object|string
     */
    public function getRules(ValidationPath $path): array|object|string
    {
        $period = Services::accountingPeriod()->current();

        if (! $period) {
            return [];
        }

        return [
            Rule::date()->afterOrEqual($period->starts_at),
            Rule::date()->beforeOrEqual($period->ends_at),
        ];
    }
}
