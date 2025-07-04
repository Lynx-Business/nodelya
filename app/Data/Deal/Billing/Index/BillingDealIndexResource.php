<?php

namespace App\Data\Deal\Billing\Index;

use App\Data\Deal\MonthlyExpenseData;
use App\Models\Client;
use App\Models\Deal;
use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BillingDealIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public float $amount,
        public string $code,
        public int $success_rate,
        public ?int $duration_in_months,
        public string $ordered_at,
        public ?string $starts_at,
        public ?Client $client,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
        public array $monthly_expenses,
    ) {}

    public static function fromModel(Deal $deal): self
    {
        return new self(
            id: $deal->id,
            name: $deal->name,
            amount: $deal->amount,
            code: $deal->code,
            success_rate: $deal->success_rate,
            duration_in_months: $deal->duration_in_months,
            ordered_at: $deal->ordered_at->toDateString(),
            starts_at: $deal->starts_at?->toDateString(),
            client: $deal->client,
            can_view: $deal->can_view,
            can_update: $deal->can_update,
            can_trash: $deal->can_trash,
            can_restore: $deal->can_restore,
            can_delete: $deal->can_delete,
            monthly_expenses: self::calculateAllMonthlyExpenses($deal),
        );
    }

    private static function calculateAllMonthlyExpenses(Deal $deal): array
    {
        $monthlyAmounts = [];
        $monthlyStatuses = $deal->monthly_status;

        if ($deal->schedule) {
            foreach ($deal->schedule as $yearSchedule) {
                foreach ($yearSchedule->data as $item) {
                    $month = Carbon::parse($item->date)->format('Y-m');
                    $monthlyAmounts[$month] = ($monthlyAmounts[$month] ?? 0) + $item->amount;
                }
            }
        }

        $result = [];
        foreach ($monthlyAmounts as $month => $amount) {
            $result[$month] = new MonthlyExpenseData(
                date: $month,
                amount: $amount,
                status: $monthlyStatuses[$month] ?? null,
            );
        }

        return $result;
    }
}
