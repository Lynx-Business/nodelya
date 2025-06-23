<?php

namespace App\Data\Expense\SubCategory\Form;

use App\Data\Expense\Category\ExpenseCategoryListResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseSubCategoryFormResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseCategoryListResource $expense_category,
        public string $name,
    ) {}
}
