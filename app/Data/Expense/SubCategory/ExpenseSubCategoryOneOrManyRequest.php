<?php

namespace App\Data\Expense\SubCategory;

use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseSubCategory;
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
class ExpenseSubCategoryOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('expenseSubCategory')]
        #[ExcludeWith('ids')]
        public ?int $expense_sub_category = null,

        #[Min(1)]
        #[RequiredWithout('expenseSubCategory')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_sub_category' => __('models.expense.sub_category.name.one'),
            'ids'                  => __('models.expense.sub_category.name.many'),
        ];
    }

    public static function rules(
        ValidationContext $context,
        #[RouteParameter('team')]
        Team $team,

        #[RouteParameter('expenseType')]
        ExpenseType $expenseType,
    ): array {
        $model = app(ExpenseSubCategory::class);

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
