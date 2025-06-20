<?php

namespace App\Actions\Expense\Category;

use App\Data\Expense\Category\Form\ExpenseCategoryFormRequest;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateExpenseCategory
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ExpenseCategoryFormRequest $data): ?ExpenseCategory
    {
        DB::beginTransaction();

        $expenseCategory = $data->expense_category;

        try {
            if (! $expenseCategory) {
                $expenseCategory = $data->team->expenseCategories()->create($data->toArray());
            } else {
                $expenseCategory->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $expenseCategory;
    }
}
