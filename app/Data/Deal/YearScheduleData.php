<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class YearScheduleData extends Data
{
    /**
     * @param  ScheduleItemData[]  $data
     */
    public function __construct(

        /** @var \App\Data\ScheduleItemData[] */
        public array $data,
    ) {}
}
