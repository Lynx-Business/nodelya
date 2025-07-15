<?php

namespace App\Data\Expense\Charge\Form;

use App\Attributes\InCurrentAccountingPeriod;
use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Models\Contractor;
use App\Models\Employee;
use App\Models\ExpenseCharge;
use App\Models\ExpenseItem;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseChargeFormRequest extends Data
{
    #[Hidden]
    #[Computed]
    public int $amount_in_cents;

    public function __construct(
        #[Hidden]
        #[FromRouteParameter('expenseCharge')]
        public ?ExpenseCharge $expense_charge,

        #[LiteralTypeScriptType("'contractor' | 'employee'")]
        public ?string $model_type,

        #[RequiredWith('model_type')]
        public ?int $model_id,

        public int $expense_item_id,

        #[Min(0)]
        public float $amount,

        #[InCurrentAccountingPeriod]
        public Carbon $charged_at,

        public Optional|int|null $deal_id = null,
    ) {
        $this->amount_in_cents = Services::conversion()->priceToCents($amount);
        $this->charged_at->startOfDay();

        if ($deal_id === null) {
            $this->deal_id = Optional::create();
        }
    }

    public static function attributes(): array
    {
        return [
            'expense_item_id' => __('models.expense.item.name.one'),
            'amount'          => __('models.expense.charge.fields.amount'),
            'charged_at'      => __('models.expense.charge.fields.charged_at'),
        ];
    }

    public static function rules(
        ValidationContext $context,

        #[FromRouteParameter('expenseCharge')]
        ?ExpenseCharge $expenseCharge = null,
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
                Rule::unique('expense_charges')
                    ->where('team_id', $team?->getKey())
                    ->where('accounting_period_id', $accountingPeriod?->id)
                    ->where('model_type', $modelType)
                    ->where('model_id', data_get($context->payload, 'model_id'))
                    ->where('charged_at', data_get($context->payload, 'charged_at'))
                    ->ignore($expenseCharge?->id),
            ],
            'model_type' => $modelTypeRules,
            'model_id'   => $modelIdRules,
        ];
    }
}
