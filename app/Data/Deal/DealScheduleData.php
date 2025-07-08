<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DealScheduleData extends Data
{
    /**
     * @param  ScheduleItemData[]  $data
     */
    public function __construct(

        public string $year,

        #[DataCollectionOf(ScheduleItemData::class)]
        public DataCollection $data,
    ) {}
}
