<?php

namespace App\Data\Deal;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class MonthlyExpenseData extends Data
{
    public function __construct(
        public string $date_key,
        public float $amount,
        public string $status,
        public Carbon $date,
    ) {}
}
