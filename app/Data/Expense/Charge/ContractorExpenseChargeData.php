<?php

namespace App\Data\Expense\Charge;

use App\Facades\Services;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * @property int|null $id
 * @property int $expense_item_id
 * @property float $amount
 * @property string $charged_at
 * @property int $contractor_id
 */
#[TypeScript]
#[MergeValidationRules]
class ContractorExpenseChargeData extends Data
{
    #[Hidden]
    #[Computed]
    public int $amount_in_cents;

    public function __construct(
        public ?int $id,
        public int $expense_item_id,
        public float $amount,
        public string $charged_at,
        public int $contractor_id,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);
    }
}
