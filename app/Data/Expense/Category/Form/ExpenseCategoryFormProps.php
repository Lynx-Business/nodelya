<?php

namespace App\Data\Expense\Category\Form;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseCategoryFormProps extends Resource
{
    public function __construct(
        public TeamListResource $team,
        public ExpenseType $expenseType,
        public ?ExpenseCategoryResource $expenseCategory,
    ) {}
}
