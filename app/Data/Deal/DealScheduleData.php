<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MapInputName(SnakeCaseMapper::class)]
#[MapOutputName(SnakeCaseMapper::class)]
class DealScheduleData extends Data
{
    /**
     * @param  array<string, YearScheduleData>  $years
     */
    public function __construct(

        #[DataCollectionOf(YearScheduleData::class)]
        public array $years,
    ) {}
}
