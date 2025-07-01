<?php

namespace App\Data\Deal\Billing\Form;

use App\Data\Client\ClientListResource;
use App\Data\Deal\DealListResource;
use App\Data\Deal\YearScheduleData;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BillingDealFormResource extends Resource
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
        public ClientListResource $client,
        public ?DealListResource $parent,
        #[DataCollectionOf(YearScheduleData::class)]
        public DataCollection $schedule,
    ) {}
}
