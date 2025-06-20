<?php

namespace App\Enums\Expense;

use App\Traits\Enums\Labels;

enum ExpenseType: string
{
    use Labels;

    case GENERAL = 'general';
    case EMPLOYEE = 'employee';
    case CONTRACTOR = 'contractor';

    public function label(): string
    {
        return __("enums.expense.type.{$this->value}");
    }
}
