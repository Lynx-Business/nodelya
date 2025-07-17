<?php

namespace App\Http\Controllers\Expense\Management\Api;

use App\Data\Expense\Charge\ExpenseChargeResource;
use App\Data\Expense\Charge\Form\ExpenseChargeFormRequest;
use App\Facades\Services;
use App\Models\ExpenseCharge;
use Illuminate\Http\Response;

class ExpenseManagementApiChargeController
{
    public function store(ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        abort_if(! $expenseCharge, Response::HTTP_BAD_REQUEST);

        return ExpenseChargeResource::from($expenseCharge)->include('can_update');
    }

    public function update(ExpenseCharge $expenseCharge, ExpenseChargeFormRequest $data)
    {
        $expenseCharge = Services::expense()->createOrUpdateCharge->execute($data);

        abort_if(! $expenseCharge, Response::HTTP_BAD_REQUEST);

        return ExpenseChargeResource::from($expenseCharge)->include('can_update');
    }
}
