<?php

namespace App\Actions\Expense\Item;

use App\Data\Expense\Item\Form\ExpenseItemFormRequest;
use App\Models\ExpenseItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateExpenseItem
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ExpenseItemFormRequest $data): ?ExpenseItem
    {
        DB::beginTransaction();

        $expenseItem = $data->expense_item;

        try {
            if (! $expenseItem) {
                $expenseItem = $data->team->expenseItems()->create($data->toArray());
            } else {
                $expenseItem->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $expenseItem;
    }
}
