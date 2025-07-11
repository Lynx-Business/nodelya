<?php

namespace App\Http\Controllers\Expense\Management\Api;

use App\Data\Expense\Management\Index\ExpenseManagementIndexRequest;
use App\Data\Expense\Management\Index\ExpenseManagementModelResource;
use App\Models\Contractor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ExpenseManagementApiContractorController
{
    public function index(ExpenseManagementIndexRequest $data)
    {
        $contractors = Contractor::query()
            ->whereInAccountingPeriod($data->accounting_period_model)
            ->withWhereHas(
                'expenseBudgets',
                fn (Builder|MorphMany $q) => $q->whereInAccountingPeriod($data->accounting_period_model),
            )
            ->with([
                'expenseCharges' => fn (Builder|MorphMany $q) => $q->whereInAccountingPeriod($data->accounting_period_model),
            ])
            ->orderBy('full_name')
            ->get();

        return ExpenseManagementModelResource::collect(
            $contractors->map(fn (Contractor $contractor) => new ExpenseManagementModelResource(
                model            : $contractor,
                accountingPeriod : $data->accounting_period_model,
            )),
        );
    }
}
