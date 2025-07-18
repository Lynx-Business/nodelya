<?php

namespace App\Data\Flow;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Enums\Flow\FlowRowType;
use App\Models\Deal;
use App\Models\ExpenseCharge;
use App\Models\FlowCategory;
use App\Models\FlowCharge;
use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FlowResource extends Data
{
    public function __construct(
        public FlowRowType $type,
        public string $name,
        public array $values,
        public ?int $categoryId = null,
    ) {}

    public static function billing(AccountingPeriodResource $accountingPeriod): array
    {
        $months = self::initializeMonths($accountingPeriod);

        $deals = Deal::billing()
            ->whereInAccountingPeriod($accountingPeriod->id)
            ->with('schedule')
            ->get();

        foreach ($deals as $deal) {
            foreach ($deal->schedule ?? [] as $schedule) {
                foreach ($schedule->data ?? [] as $item) {
                    $monthKey = $item->date->format('Y-m');
                    if (isset($months[$monthKey])) {
                        $months[$monthKey] += $item->amount;
                    }
                }
            }
        }

        return [
            'name'   => __('models.flow.name'),
            'values' => $months,
        ];
    }

    public static function flowCategory(FlowCategory $category, AccountingPeriodResource $accountingPeriod): array
    {
        $months = self::initializeMonths($accountingPeriod);

        $category->flowCharges->each(function (FlowCharge $charge) use (&$months) {
            $monthKey = $charge->charged_at->format('Y-m');
            if (isset($months[$monthKey])) {
                $months[$monthKey] += $charge->amount;
            }
        });

        return [
            'name'       => $category->name,
            'categoryId' => $category->id,
            'values'     => $months,
        ];
    }

    /**
     * Retourne un tableau des charges (ExpenseCharge) par mois pour un accountingPeriod donné.
     *
     * @return array<string, float>
     */
    public static function expenseCharges(AccountingPeriodResource $accountingPeriod): array
    {
        $months = self::initializeMonths($accountingPeriod);

        $charges = ExpenseCharge::query()
            ->whereBelongsToAccountingPeriod($accountingPeriod->id)
            ->get();

        foreach ($charges as $charge) {
            $monthKey = $charge->charged_at->format('Y-m');
            if (isset($months[$monthKey])) {
                $months[$monthKey] += $charge->amount;
            }
        }

        return [
            'name'   => 'Dépenses prévisionnelles',
            'values' => $months,
        ];
    }

    private static function initializeMonths(AccountingPeriodResource $accountingPeriod): array
    {
        return $accountingPeriod->months
            ->mapWithKeys(fn (Carbon $month) => [
                $month->format('Y-m') => 0,
            ])
            ->all();
    }
}
