<?php

namespace App\Actions\Expense\Budget;

use App\Data\Expense\Budget\Form\ExpenseBudgetFormRequest;
use App\Facades\Services;
use App\Models\ExpenseBudget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateExpenseBudget
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ExpenseBudgetFormRequest $data): ?ExpenseBudget
    {
        DB::beginTransaction();

        $expenseBudget = $data->expense_budget;

        try {
            if (! $expenseBudget) {
                $expenseBudget = Services::team()->current()->expenseBudgets()->create($data->toArray());
            } else {
                $expenseBudget->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $expenseBudget;
    }
}
