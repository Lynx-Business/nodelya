<?php

namespace App\Data\Expense\Category;

use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseCategoryListResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public string $name,
    ) {}
}
