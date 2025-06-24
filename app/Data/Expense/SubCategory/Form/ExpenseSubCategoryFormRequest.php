<?php

namespace App\Data\Expense\SubCategory\Form;

use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSubCategory;
use App\Models\Team;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseSubCategoryFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('team')]
        public Team $team,

        #[Hidden]
        #[FromRouteParameter('expenseSubCategory')]
        public ?ExpenseSubCategory $expense_sub_category,

        public int $expense_category_id,

        public string $name,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_category_id' => __('models.expense.category.name.one'),
            'name'                => __('models.expense.sub_category.fields.name'),
        ];
    }

    public static function rules(
        ValidationContext $context,

        #[RouteParameter('team')]
        Team $team,

        #[RouteParameter('expenseType')]
        ExpenseType $expenseType,
    ): array {
        $expenseCategory = app(ExpenseCategory::class);

        return [
            'expense_category_id' => [
                'integer',
                Rule::exists($expenseCategory->getTable(), $expenseCategory->getKeyName())
                    ->where($expenseCategory->getQualifiedTeamIdColumn(), $team->getKey())
                    ->where('type', $expenseType),
            ],
        ];
    }
}
