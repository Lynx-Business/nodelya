<?php

namespace App\Data\Expense\Charge;

use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * @property int $expense_item_id
 * @property float $amount
 * @property string $charged_at
 * @property int $contractor_id
 */
#[TypeScript]
#[MergeValidationRules]
class ContractorExpenseChargeData extends Data
{
    public function __construct(
        public int $expense_item_id,
        public float $amount,
        public string $charged_at,
        public int $contractor_id,
    ) {}
}
