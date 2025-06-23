<?php

namespace App\Data\Expense\SubCategory;

use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseSubCategoryListResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public string $name,
    ) {}
}
