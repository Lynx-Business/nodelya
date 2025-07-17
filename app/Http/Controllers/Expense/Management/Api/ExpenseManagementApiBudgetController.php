<?php

namespace App\Http\Controllers\Expense\Management\Api;

use App\Data\Expense\Management\Index\ExpenseManagementBudgetResource;
use App\Data\Expense\Management\Index\ExpenseManagementIndexRequest;
use App\Models\ExpenseBudget;
use App\Models\ExpenseCharge;
use Illuminate\Database\Eloquent\Builder;

class ExpenseManagementApiBudgetController
{
    public function index(ExpenseManagementIndexRequest $data)
    {
        $accountingPeriod = $data->accounting_period_model;
        $modelType = $data->model_type;
        $modelId = $data->model_id;

        $budgets = ExpenseBudget::query()
            ->whereBelongsToAccountingPeriod($accountingPeriod)
            ->when(
                $modelType && $modelId,
                fn (Builder $q) => $q->where([
                    'model_type' => $modelType,
                    'model_id'   => $modelId,
                ]),
                fn (Builder $q) => $q->whereNull('model_type'),
            )
            ->with([
                'accountingPeriod',
                'expenseItem',
            ])
            ->get();

        $charges = ExpenseCharge::query()
            ->whereBelongsToAccountingPeriod($accountingPeriod)
            ->whereIntegerInRaw('expense_item_id', $budgets->pluck('expense_item_id'))
            ->when(
                $modelType && $modelId,
                fn (Builder $q) => $q->where([
                    'model_type' => $modelType,
                    'model_id'   => $modelId,
                ]),
                fn (Builder $q) => $q->whereNull('model_type'),
            )
            ->get();

        return ExpenseManagementBudgetResource::collect(
            $budgets->map(
                fn (ExpenseBudget $budget) => new ExpenseManagementBudgetResource(
                    expenseBudget    : $budget,
                    expenseCharges   : $charges->where('expense_item_id', $budget->expense_item_id),
                    accountingPeriod : $accountingPeriod,
                ),
            ),
        );
    }
}
