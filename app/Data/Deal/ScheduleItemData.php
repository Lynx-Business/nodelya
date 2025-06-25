<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ScheduleItemData extends Data
{
    public function __construct(
        #[Required,
            Date]
        public string $date,

        #[Required,
            Numeric]
        public float $amount,

        #[Required,
            StringType]
        public string $status,

        #[Required,
            StringType]
        public string $title,
    ) {}
}
