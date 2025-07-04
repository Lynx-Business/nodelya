<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MonthlyExpenseData extends Data
{
    public function __construct(
        // TODO change to Date carbon
        public string $date,
        public float $amount,
        public ?string $status,
    ) {}
}
