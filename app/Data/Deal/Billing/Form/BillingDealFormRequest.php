<?php

namespace App\Data\Deal\Billing\Form;

use App\Data\Deal\DealScheduleData;
use App\Data\Deal\ScheduleItemData;
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
class BillingDealFormRequest extends Data
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

        public string $ordered_at,

        public ?int $duration_in_months,

        public ?string $starts_at,

        public int $client_id,

        public ?int $deal_id,

        public ?int $project_department_id,

        #[Min(1)]
        #[DataCollectionOf(ScheduleItemData::class)]
        public array $schedule_data,

        #[DataCollectionOf(DealScheduleData::class)]
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
            'ordered_at'         => __('models.commercial_deal.fields.ordered_at'),
            'duration_in_months' => __('models.commercial_deal.fields.duration_in_months'),
            'starts_at'          => __('models.commercial_deal.fields.starts_at'),
            'client_id'          => __('models.commercial_deal.fields.client_id'),
            'deal_id'            => __('models.commercial_deal.fields.deal_id'),
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
            $years[] = DealScheduleData::from([
                'year' => $year,
                'data' => $data,
            ]);
        }

        return new DataCollection(DealScheduleData::class, $years);
    }

    public static function rules(): array
    {
        return [
            'schedule_data' => ['array', 'min:1', function ($attribute, $value, $fail) {
                $principalAmount = request()->input('amount');
                $totalSchedule = collect($value)->sum('amount');

                if ($totalSchedule !== $principalAmount) {
                    $fail(__('validation.custom.schedule_total_exceeds', [
                        'total'     => $totalSchedule,
                        'principal' => $principalAmount,
                    ]));
                }

                foreach ($value as $item) {
                    if (empty($item['title'])) {
                        $fail(__('validation.required', ['attribute' => __('models.commercial_deal.fields.schedule_data.title')]));
                        break;
                    }
                }
            }],
        ];
    }
}
