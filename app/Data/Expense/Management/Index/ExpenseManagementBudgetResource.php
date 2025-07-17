<?php

namespace App\Data\Expense\Management\Index;

use App\Data\Expense\Item\ExpenseItemResource;
use App\Models\AccountingPeriod;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCharge;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseManagementBudgetResource extends Resource
{
    #[Computed]
    public ExpenseItemResource $expense_item;

    #[Computed]
    #[DataCollectionOf(ExpenseManagementCellResource::class)]
    public DataCollection $cells;

    public function __construct(
        ExpenseBudget $expenseBudget,

        /** @var Collection<\App\Models\ExpenseCharge> */
        Collection $expenseCharges,

        AccountingPeriod $accountingPeriod,
    ) {
        $expense_item = $expenseBudget->expenseItem;
        $this->expense_item = ExpenseItemResource::from($expense_item)->only('id', 'name');

        $this->cells = ExpenseManagementCellResource::collect(
            $accountingPeriod->months->map(function (Carbon $month) use ($expenseBudget, $expenseCharges) {
                /** @var ?\App\Models\ExpenseCharge $expenseCharge */
                $expenseCharge = $expenseCharges
                    ->where('expense_item_id', $expenseBudget->expense_item_id)
                    ->where('charged_at', $month->copy()->startOfMonth())
                    ->first();

                return [
                    'budget'     => $expenseBudget->monthly_amount ?? 0,
                    'charge'     => $expenseCharge?->amount ?? 0,
                    'charge_id'  => $expenseCharge?->getKey(),
                    'can_update' => $expenseCharge?->can_update ?? Gate::check('create', ExpenseCharge::class),
                ];
            }),
            DataCollection::class,
        );
    }
}
