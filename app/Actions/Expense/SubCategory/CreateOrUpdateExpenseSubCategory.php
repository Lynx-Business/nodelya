<?php

namespace App\Actions\Expense\SubCategory;

use App\Data\Expense\SubCategory\Form\ExpenseSubCategoryFormRequest;
use App\Models\ExpenseSubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateExpenseSubCategory
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ExpenseSubCategoryFormRequest $data): ?ExpenseSubCategory
    {
        DB::beginTransaction();

        $expenseSubCategory = $data->expense_sub_category;

        try {
            if (! $expenseSubCategory) {
                $expenseSubCategory = $data->team->expenseSubCategories()->create($data->toArray());
            } else {
                $expenseSubCategory->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $expenseSubCategory;
    }
}
