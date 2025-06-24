<?php

namespace App\Data\Expense\Item;

use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseItem;
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
class ExpenseItemOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('expenseItem')]
        #[ExcludeWith('ids')]
        public ?int $expense_item = null,

        #[Min(1)]
        #[RequiredWithout('expenseItem')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'expense_item' => __('models.expense.item.name.one'),
            'ids'          => __('models.expense.item.name.many'),
        ];
    }

    public static function rules(
        ValidationContext $context,

        #[RouteParameter('team')]
        Team $team,

        #[RouteParameter('expenseType')]
        ExpenseType $expenseType,
    ): array {
        $model = app(ExpenseItem::class);

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
