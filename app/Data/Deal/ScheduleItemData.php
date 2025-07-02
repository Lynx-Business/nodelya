<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ScheduleItemData extends Data
{
    public function __construct(
        #[Date]
        public string $date,

        public float $amount,

        public ?string $status,

        public ?string $title,
    ) {}
}
