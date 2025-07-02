<?php

namespace App\Data\Expense\Budget;

use App\Facades\Services;
use App\Models\ExpenseBudget;
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
class ExpenseBudgetOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('expenseBudget')]
        #[ExcludeWith('ids')]
        public ?int $expense_budget = null,

        #[Min(1)]
        #[RequiredWithout('expenseBudget')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_budget' => __('models.expense.budget.name.one'),
            'ids'            => __('models.expense.budget.name.many'),
        ];
    }

    public static function rules(
        ValidationContext $context,
    ): array {
        $model = app(ExpenseBudget::class);
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
