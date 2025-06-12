<?php

namespace App\Enums\Expense\Category;

use App\Traits\Enums\Labels;

enum ExpenseCategoryType: string
{
    use Labels;

    case GENERAL = 'general';
    case EMPLOYEE = 'employee';
    case CONTRACTOR = 'contractor';

    public function label(): string
    {
        return __("enums.expense_category.type.{$this->value}");
    }
}
