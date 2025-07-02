<?php

namespace App\Data\Expense\Charge;

use App\Facades\Services;
use App\Models\ExpenseCharge;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\ExcludeWith;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseChargeOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('expenseCharge')]
        #[ExcludeWith('ids')]
        public ?int $expense_charge = null,

        #[Min(1)]
        #[RequiredWithout('expenseCharge')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_charge' => __('models.expense.charge.name.one'),
            'ids'            => __('models.expense.charge.name.many'),
        ];
    }

    public static function rules(
        ValidationContext $context,
    ): array {
        $model = app(ExpenseCharge::class);
        $team = Services::team()->current();

        return [
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists($model->getTable(), $model->getKeyName())
                    ->where($model->getQualifiedTeamIdColumn(), $team->getKey()),
            ],
        ];
    }
}
