<?php

namespace App\Data\Expense\Charge;

use App\Data\Contractor\ContractorResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Facades\Services;
use App\Models\Contractor;
use App\Models\ExpenseItem;
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

    #[Computed]
    public ?ExpenseItemResource $expense_item;

    #[Computed]
    public ?ContractorResource $contractor;

    public function __construct(
        public ?int $id,
        public int $expense_item_id,
        public float $amount,
        public string $charged_at,
        public int $contractor_id,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);

        if ($expense_item_id) {
            $expenseItem = ExpenseItem::find($expense_item_id);
            if ($expenseItem) {
                $this->expense_item = ExpenseItemResource::from($expenseItem);
            }
        }

        if ($contractor_id) {
            $contractor = Contractor::find($contractor_id);
            if ($contractor) {
                $this->contractor = ContractorResource::from($contractor);
            }
        }
    }
}
