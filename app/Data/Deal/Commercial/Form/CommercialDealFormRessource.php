<?php

namespace App\Data\Deal\Commercial\Form;

use App\Data\Deal\DealScheduleData;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealFormResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public float $amount,
        public ?string $code,
        public ?string $reference,
        public int $success_rate,
        public string $ordered_at,
        public int $duration_in_months,
        public string $starts_at,
        public DealScheduleData $schedule,
    ) {}
}
