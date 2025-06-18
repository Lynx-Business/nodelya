<?php

namespace App\Data\AccountingPeriod\Form;

use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountingPeriodFormResource extends Resource
{
    public function __construct(
        public int $id,
        public string $label,
        public Carbon $starts_at,
        public Carbon $ends_at,
    ) {}
}
