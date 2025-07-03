<?php

namespace App\Data\Deal;

use App\Enums\Deal\DealScheduleStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ScheduleItemData extends Data
{
    public function __construct(

        public Carbon $date,

        public float $amount,

        public DealScheduleStatus $status,

        public ?string $title,
    ) {}
}
