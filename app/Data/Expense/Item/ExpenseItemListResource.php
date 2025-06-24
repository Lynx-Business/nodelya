<?php

namespace App\Data\Expense\Item;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseItemListResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
