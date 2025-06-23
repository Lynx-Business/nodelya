<?php

namespace App\Services;

use App\Actions\Expense\Category\CreateOrUpdateExpenseCategory;
use App\Actions\Expense\SubCategory\CreateOrUpdateExpenseSubCategory;

class ExpenseService
{
    public function __construct(
        public CreateOrUpdateExpenseCategory $createOrUpdateCategory,
        public CreateOrUpdateExpenseSubCategory $createOrUpdateSubCategory,
    ) {}
}
