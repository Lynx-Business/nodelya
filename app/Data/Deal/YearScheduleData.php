<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class YearScheduleData extends Data
{
    /**
     * @param  ScheduleItemData[]  $data
     */
    public function __construct(

        public string $year,

        #[DataCollectionOf(ScheduleItemData::class)]
        public array $data,
    ) {}
}
