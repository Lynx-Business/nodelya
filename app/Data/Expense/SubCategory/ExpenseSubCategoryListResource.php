<?php

namespace App\Data\Expense\SubCategory;

use App\Data\Expense\Category\ExpenseCategoryListResource;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseSubCategoryListResource extends Resource
{
    public function __construct(
        public int $id,
        public Optional|ExpenseCategoryListResource $expense_category,
        public string $name,
    ) {}
}
