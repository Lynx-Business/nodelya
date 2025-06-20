<?php

namespace App\Data\Expense\Category\Form;

use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseCategoryFormResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public string $name,
    ) {}
}
