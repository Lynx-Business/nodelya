<?php

namespace App\Actions\Expense\Charge;

use App\Data\Expense\Charge\Form\ExpenseChargeFormRequest;
use App\Facades\Services;
use App\Models\ExpenseCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateExpenseCharge
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ExpenseChargeFormRequest $data): ?ExpenseCharge
    {
        DB::beginTransaction();

        $expenseCharge = $data->expense_charge;

        try {
            if (! $expenseCharge) {
                $expenseCharge = Services::team()->current()->expenseCharges()->create($data->toArray());
            } else {
                $expenseCharge->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $expenseCharge;
    }
}
