<?php

namespace App\Data\Expense\Category;

use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseCategory;
use App\Models\Team;
use Illuminate\Container\Attributes\RouteParameter;
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
class ExpenseCategoryOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('expenseCategory')]
        #[ExcludeWith('ids')]
        public ?int $expense_category = null,

        #[Min(1)]
        #[RequiredWithout('expenseCategory')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_category' => __('models.expense.category.name.one'),
            'ids'              => __('models.expense.category.name.many'),
        ];
    }

    public static function rules(
        ValidationContext $context,
        #[RouteParameter('team')]
        Team $team,

        #[RouteParameter('expenseType')]
        ExpenseType $expenseType,
    ): array {
        $model = app(ExpenseCategory::class);

        return [
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists($model->getTable(), $model->getKeyName())
                    ->where($model->getQualifiedTeamIdColumn(), $team->getKey())
                    ->where('type', $expenseType),
            ],
        ];
    }
}
