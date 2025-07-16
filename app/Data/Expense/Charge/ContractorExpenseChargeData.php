<?php

namespace App\Data\Expense\Charge;

use App\Data\Contractor\ContractorResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Facades\Services;
use App\Models\Contractor;
use App\Models\ExpenseItem;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

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

        #[Min(0)]
        public float $amount,

        public Carbon $charged_at,

        public int $contractor_id,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);

        if ($expense_item_id) {
            $expenseItem = ExpenseItem::findOrFail($expense_item_id);
            if ($expenseItem) {
                $this->expense_item = ExpenseItemResource::from($expenseItem);
            }
        }

        if ($contractor_id) {
            $contractor = Contractor::findOrFail($contractor_id);
            if ($contractor) {
                $this->contractor = ContractorResource::from($contractor);
            }
        }
    }

    public static function rules(ValidationContext $context): array
    {

        return [
            'expense_item_id' => ['required', 'integer', 'exists:expense_items,id'],
            'contractor_id'   => ['nullable', 'integer', 'exists:contractors,id'],
        ];
    }
}
