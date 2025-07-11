<?php

namespace App\Data\Expense\Management\Index;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseManagementCellResource extends Resource
{
    public function __construct(
        public float $budget,

        public float $charge,
    ) {
        $this->budget = round($budget, 2);
        $this->charge = round($charge, 2);
    }
}
