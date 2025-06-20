<?php

namespace App\Services;

use App\Actions\Expense\Category\CreateOrUpdateExpenseCategory;

class ExpenseService
{
    public function __construct(
        public CreateOrUpdateExpenseCategory $createOrUpdateCategory,
    ) {}
}
