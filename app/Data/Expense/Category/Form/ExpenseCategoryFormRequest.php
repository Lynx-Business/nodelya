<?php

namespace App\Data\Expense\Category\Form;

use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseCategory;
use App\Models\Team;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseCategoryFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('team')]
        public Team $team,

        #[Hidden]
        #[FromRouteParameter('expenseCategory')]
        public ?ExpenseCategory $expense_category,

        #[Hidden]
        #[FromRouteParameter('expenseType')]
        public ExpenseType $type,

        public string $name,
    ) {}

    public static function attributes(): array
    {
        return [
            'name' => __('models.expense.category.fields.name'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            //
        ];
    }
}
