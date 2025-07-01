<?php

namespace App\Data\Expense\Item\Index;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use App\Models\ExpenseCategory;
use App\Models\ExpenseSubCategory;
use App\Models\Team;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseItemIndexRequest extends Data
{
    #[Computed]
    #[DataCollectionOf(ExpenseCategoryResource::class)]
    public ?DataCollection $expense_categories;

    #[Computed]
    #[DataCollectionOf(ExpenseSubCategoryResource::class)]
    public ?DataCollection $expense_sub_categories;

    public function __construct(
        public ?string $q = null,
        public ?int $page = null,
        public ?int $per_page = null,
        public string $sort_by = 'id',
        public string $sort_direction = 'desc',
        public ?TrashedFilter $trashed = null,

        /** @var array<int> $expense_category_ids */
        public ?array $expense_category_ids = null,

        /** @var array<int> $expense_sub_category_ids */
        public ?array $expense_sub_category_ids = null,
    ) {
        if ($expense_category_ids) {
            $this->expense_categories = ExpenseCategoryResource::collect(
                ExpenseCategory::query()
                    ->whereIntegerInRaw('id', $expense_category_ids)
                    ->get(),
                DataCollection::class,
            );
        }
        if ($expense_sub_category_ids) {
            $this->expense_sub_categories = ExpenseSubCategoryResource::collect(
                ExpenseSubCategory::query()
                    ->whereIntegerInRaw('id', $expense_sub_category_ids)
                    ->get(),
                DataCollection::class,
            );
        }
    }

    public static function attributes(): array
    {
        return [
            'q'                        => __('query'),
            'page'                     => __('page'),
            'per_page'                 => __('per_page'),
            'sort_by'                  => __('sort_by'),
            'sort_direction'           => __('sort_direction'),
            'trashed'                  => __('trashed'),
            'expense_category_ids'     => __('models.expense.category.name.many'),
            'expense_sub_category_ids' => __('models.expense.sub_category.name.many'),
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
            'expense_category_ids.*' => [
                'integer',
                Rule::exists($expenseCategory->getTable(), $expenseCategory->getKeyName())
                    ->where($expenseCategory->getQualifiedTeamIdColumn(), $team->getKey())
                    ->where('type', $expenseType),
            ],
            'expense_sub_category_ids.*' => [
                'integer',
                Rule::exists($expenseSubCategory->getTable(), $expenseSubCategory->getKeyName())
                    ->where($expenseSubCategory->getQualifiedTeamIdColumn(), $team->getKey()),
            ],
        ];
    }
}
