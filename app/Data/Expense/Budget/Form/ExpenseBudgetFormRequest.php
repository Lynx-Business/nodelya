<?php

namespace App\Data\Expense\Budget\Form;

use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Models\Contractor;
use App\Models\Employee;
use App\Models\ExpenseBudget;
use App\Models\ExpenseItem;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseBudgetFormRequest extends Data
{
    #[Hidden]
    #[Computed]
    public int $amount_in_cents;

    #[Hidden]
    #[Computed]
    public int $accounting_period_id;

    public function __construct(
        #[Hidden]
        #[FromRouteParameter('expenseBudget')]
        public ?ExpenseBudget $expense_budget,

        #[LiteralTypeScriptType("'contractor' | 'employee'")]
        public ?string $model_type,

        #[RequiredWith('model_type')]
        public ?int $model_id,

        public int $expense_item_id,

        #[Min(0)]
        public float $amount,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);

        $this->accounting_period_id = Services::accountingPeriod()->currentId();
    }

    public static function attributes(): array
    {
        return [
            'expense_item_id' => __('models.expense.item.name.one'),
            'amount'          => __('models.expense.budget.fields.amount'),
        ];
    }

    public static function rules(
        ValidationContext $context,

        #[FromRouteParameter('expenseBudget')]
        ?ExpenseBudget $expenseBudget = null,
    ): array {
        $team = Services::team()->current();
        $accountingPeriod = Services::accountingPeriod()->current();
        $modelType = data_get($context->payload, 'model_type');
        $expenseType = ExpenseType::fromMorphType($modelType);

        $modelClass = match ($modelType) {
            Relation::getMorphAlias(Contractor::class) => Contractor::class,
            Relation::getMorphAlias(Employee::class)   => Employee::class,
            default                                    => null
        };

        $model = $modelClass ? app($modelClass) : null;
        $modelTypeRules = $model
            ? [
                'nullable', Rule::in([
                    Relation::getMorphAlias(Contractor::class),
                    Relation::getMorphAlias(Employee::class),
                ]),
            ]
            : ['exclude'];
        $modelIdRules = $model
            ? [
                Rule::exists($model->getTable(), $model->getKeyName())
                    ->where($model->getQualifiedTeamIdColumn(), $team?->getKey()),
            ]
            : ['exclude'];

        return [
            'expense_item_id' => [
                Rule::in(
                    ExpenseItem::query()
                        ->whereType($expenseType)
                        ->pluck('id'),
                ),
                Rule::unique(app(ExpenseBudget::class)->getTable())
                    ->where('team_id', $team?->getKey())
                    ->where('accounting_period_id', $accountingPeriod?->id)
                    ->whereNull('model_type')
                    ->whereNull('model_id')
                    ->ignore($expenseBudget?->id),
            ],
            'model_type' => $modelTypeRules,
            'model_id'   => $modelIdRules,
        ];
    }
}
