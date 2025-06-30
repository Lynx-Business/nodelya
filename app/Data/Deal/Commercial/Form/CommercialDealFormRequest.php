<?php

namespace App\Data\Deal\Commercial\Form;

use App\Data\Deal\ScheduleItemData;
use App\Data\Deal\YearScheduleData;
use App\Models\Deal;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class CommercialDealFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('deal')]
        public ?Deal $deal,

        #[Max(255)]
        public string $name,

        public float $amount,

        public string $code,

        public ?string $reference,

        #[Min(0), Max(100)]
        public int $success_rate,

        public string $ordered_at,

        public ?int $duration_in_months,

        public ?string $starts_at,

        public int $client_id,

        #[Min(1)]
        #[DataCollectionOf(ScheduleItemData::class)]
        public array $schedule_data,

        #[DataCollectionOf(YearScheduleData::class)]
        public ?DataCollection $schedule = null,

    ) {

        $this->schedule = $this->transformSchedule($schedule_data);
    }

    public static function attributes(): array
    {
        return [
            'name'               => __('models.commercial_deal.fields.name'),
            'amount_in_cents'    => __('models.commercial_deal.fields.amount'),
            'code'               => __('models.commercial_deal.fields.code'),
            'reference'          => __('models.commercial_deal.fields.reference'),
            'success_rate'       => __('models.commercial_deal.fields.success_rate'),
            'ordered_at'         => __('models.commercial_deal.fields.ordered_at'),
            'duration_in_months' => __('models.commercial_deal.fields.duration_in_months'),
            'starts_at'          => __('models.commercial_deal.fields.starts_at'),
            'client_id'          => __('models.commercial_deal.fields.client_id'),
            'schedule_data'      => __('models.commercial_deal.fields.schedule'),
        ];
    }

    private function transformSchedule(?array $schedule): ?DataCollection
    {
        if (! $schedule || empty($schedule)) {
            return null;
        }

        $yearsMap = [];
        foreach ($schedule as $item) {
            $year = explode('-', $item->date)[0];
            $yearsMap[$year][] = $item;
        }

        $years = [];
        foreach ($yearsMap as $year => $data) {
            $years[] = YearScheduleData::from([
                'year' => $year,
                'data' => $data,
            ]);
        }

        return new DataCollection(YearScheduleData::class, $years);
    }
}
