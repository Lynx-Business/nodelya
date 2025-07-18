<?php

namespace App\Data\Treasury;

use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TreasuryMonthResource extends Resource
{
    public function __construct(
        public Carbon $date,

        public float $real_amount = 0,

        public float $planned_amount = 0,
    ) {}
}
