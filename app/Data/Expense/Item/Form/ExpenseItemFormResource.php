<?php

namespace App\Data\Expense\Item\Form;

use App\Data\Expense\Category\ExpenseCategoryListResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryListResource;
use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseItemFormResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public ExpenseCategoryListResource $expense_category,
        public ?ExpenseSubCategoryListResource $expense_sub_category,
        public string $name,
    ) {}
}
