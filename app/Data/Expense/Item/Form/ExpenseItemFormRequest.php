<?php

namespace App\Data\Expense\Item\Form;

use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
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
class ExpenseItemFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('team')]
        public Team $team,

        #[Hidden]
        #[FromRouteParameter('expenseItem')]
        public ?ExpenseItem $expense_item,

        public int $expense_category_id,

        public ?int $expense_sub_category_id,

        public string $name,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_category_id'     => __('models.expense.category.name.one'),
            'expense_sub_category_id' => __('models.expense.sub_category.name.one'),
            'name'                    => __('models.expense.item.fields.name'),
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
        $expenseSubCategory = app(ExpenseSubCategory::class);

        return [
            'expense_category_id' => [
                'integer',
                Rule::exists($expenseCategory->getTable(), $expenseCategory->getKeyName())
                    ->where($expenseCategory->getQualifiedTeamIdColumn(), $team->getKey())
                    ->where('type', $expenseType),
            ],
            'expense_sub_category_id' => [
                'integer',
                Rule::exists($expenseSubCategory->getTable(), $expenseSubCategory->getKeyName())
                    ->where($expenseSubCategory->getQualifiedTeamIdColumn(), $team->getKey())
                    ->where($expenseSubCategory->getQualifiedExpenseCategoryIdColumn(), data_get($context->payload, 'expense_category_id')),
            ],
        ];
    }
}
