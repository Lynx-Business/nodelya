<?php

namespace App\Data\Expense\Charge\Index;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
use App\Models\ExpenseSubCategory;
use Carbon\Carbon;
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
class ExpenseChargeIndexRequest extends Data
{
    #[Computed]
    #[DataCollectionOf(ExpenseCategoryResource::class)]
    public ?DataCollection $expense_categories;

    #[Computed]
    #[DataCollectionOf(ExpenseSubCategoryResource::class)]
    public ?DataCollection $expense_sub_categories;

    #[Computed]
    #[DataCollectionOf(ExpenseItemResource::class)]
    public ?DataCollection $expense_items;

    public function __construct(
        public ?string $q = null,
        public ?int $page = null,
        public ?int $per_page = null,
        public string $sort_by = 'id',
        public string $sort_direction = 'desc',
        public ?TrashedFilter $trashed = null,

        /** @var null|array<int> $expense_category_ids */
        public ?array $expense_category_ids = null,

        /** @var null|array<int> $expense_sub_category_ids */
        public ?array $expense_sub_category_ids = null,

        /** @var null|array<int> $expense_item_ids */
        public ?array $expense_item_ids = null,

        public ?Carbon $starts_at = null,

        public ?Carbon $ends_at = null,
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
        if ($expense_item_ids) {
            $this->expense_items = ExpenseItemResource::collect(
                ExpenseItem::query()
                    ->whereIntegerInRaw('id', $expense_item_ids)
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
            'starts_at'                => __('start'),
            'ends_at'                  => __('end'),
        ];
    }

    public static function rules(
        ValidationContext $context,
    ): array {
        $expenseCategory = app(ExpenseCategory::class);
        $expenseSubCategory = app(ExpenseSubCategory::class);
        $expenseItem = app(ExpenseItem::class);
        $team = Services::team()->current();

        return [
            'expense_category_ids.*' => [
                'integer',
                Rule::exists($expenseCategory->getTable(), $expenseCategory->getKeyName())
                    ->where($expenseCategory->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
            'expense_sub_category_ids.*' => [
                'integer',
                Rule::exists($expenseSubCategory->getTable(), $expenseSubCategory->getKeyName())
                    ->where($expenseSubCategory->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
            'expense_item_ids.*' => [
                'integer',
                Rule::exists($expenseItem->getTable(), $expenseItem->getKeyName())
                    ->where($expenseItem->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
        ];
    }
}
