<?php

namespace App\Data\Expense\Budget\Form;

use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Models\ExpenseBudget;
use App\Models\ExpenseItem;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Before;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\References\FieldReference;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use Spatie\TypeScriptTransformer\Attributes\TypeScriptType;

#[TypeScript]
#[MergeValidationRules]
class ExpenseBudgetFormRequest extends Data
{
    #[Hidden]
    #[Computed]
    public int $amount_in_cents;

    public function __construct(
        #[Hidden]
        #[FromRouteParameter('expenseBudget')]
        public ?ExpenseBudget $expense_budget,

        #[TypeScriptType("'contractor' | 'employee'")]
        public ?string $model_type,

        #[RequiredWith('model_type')]
        public ?int $model_id,

        public int $expense_item_id,

        #[Min(0)]
        public float $amount,

        #[Before(new FieldReference('ends_at'))]
        public Carbon $starts_at,

        #[After(new FieldReference('starts_at'))]
        public Carbon $ends_at,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);
    }

    public static function attributes(): array
    {
        return [
            'expense_item_id' => __('models.expense.item.name.one'),
        ];
    }

    public static function rules(
        ValidationContext $context,
    ): array {
        $expenseType = ExpenseType::fromMorphType(data_get($context->payload, 'model_type'));
        $expenseItem = ExpenseItem::query()
            ->whereType($expenseType)
            ->find(data_get($context->payload, 'expense_item_id'));

        if ($expenseItem) {
            return [];
        }

        // Invalid
        return [
            'expense_item_id' => [Rule::in([])],
        ];
    }
}
