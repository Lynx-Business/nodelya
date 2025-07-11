<?php

namespace App\Data\Expense\Management\Index;

use App\Models\AccountingPeriod;
use App\Models\Contractor;
use App\Models\Employee;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseManagementModelResource extends Resource
{
    #[Computed]
    public string $model_type;

    #[Computed]
    public int $model_id;

    #[Computed]
    public string $label;

    #[Computed]
    #[DataCollectionOf(ExpenseManagementCellResource::class)]
    public DataCollection $cells;

    public function __construct(
        Contractor|Employee $model,

        AccountingPeriod $accountingPeriod,
    ) {
        $this->model_type = $model->getMorphClass();
        $this->model_id = $model->getKey();
        $this->label = match ($model::class) {
            Contractor::class, Employee::class => $model->full_name,
        };

        $this->cells = ExpenseManagementCellResource::collect(
            $accountingPeriod->months->map(fn (Carbon $month) => [
                'budget' => $model->expenseBudgets->sum->monthly_amount,
                'charge' => $model->expenseCharges
                    ->whereIn('expense_item_id', $model->expenseBudgets->pluck('expense_item_id'))
                    ->where('charged_at', $month)
                    ->sum->amount,
            ]),
            DataCollection::class,
        );
    }
}
