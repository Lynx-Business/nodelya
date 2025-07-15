<?php

namespace App\Data\Expense\Management\Index;

use App\Enums\Expense\ExpenseType;
use App\Models\AccountingPeriod;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCharge;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseManagementTypeResource extends Resource
{
    #[Computed]
    #[DataCollectionOf(ExpenseManagementCellResource::class)]
    public DataCollection $cells;

    public function __construct(
        public ExpenseType $type,

        AccountingPeriod $accountingPeriod,
    ) {
        $budgets = ExpenseBudget::query()
            ->whereBelongsToAccountingPeriod($accountingPeriod)
            ->whereType($type)
            ->with(['accountingPeriod'])
            ->get();
        $charges = ExpenseCharge::query()
            ->whereBelongsToAccountingPeriod($accountingPeriod)
            ->whereType($type)
            ->whereIntegerInRaw('expense_item_id', $budgets->pluck('expense_item_id'))
            ->get();

        $this->cells = ExpenseManagementCellResource::collect(
            $accountingPeriod->months->map(fn (Carbon $month) => [
                'budget' => $budgets->sum->monthly_amount,
                'charge' => $charges
                    ->where('charged_at', $month)
                    ->sum->amount,
            ]),
            DataCollection::class,
        );
    }
}
