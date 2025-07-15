<?php

namespace App\Data\Expense\Management\Index;

use App\Attributes\EnumArrayOf;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Expense\ExpenseType;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\ExpenseCategory;
use App\Models\ExpenseItem;
use App\Models\ExpenseSubCategory;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden as TypeScriptHidden;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ExpenseManagementIndexRequest extends Data
{
    #[Computed]
    #[Hidden]
    #[TypeScriptHidden]
    public AccountingPeriod $accounting_period_model;

    #[Computed]
    public AccountingPeriodResource $accounting_period;

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

        #[LiteralTypeScriptType("'contractor' | 'employee'")]
        public ?string $model_type = null,

        public ?int $model_id = null,

        #[EnumArrayOf(ExpenseType::class)]
        public ?array $expense_types = null,

        public ?int $accounting_period_id = null,

        /** @var null|array<int> $expense_category_ids */
        public ?array $expense_category_ids = null,

        /** @var null|array<int> $expense_sub_category_ids */
        public ?array $expense_sub_category_ids = null,

        /** @var null|array<int> $expense_item_ids */
        public ?array $expense_item_ids = null,
    ) {
        if ($accounting_period_id) {
            $this->accounting_period_model = AccountingPeriod::find($accounting_period_id);
        } else {
            $this->accounting_period_model = Services::accountingPeriod()->current();
            $this->accounting_period_id = Services::accountingPeriod()->currentId();
        }
        if ($this->accounting_period_model) {
            $this->accounting_period = AccountingPeriodResource::from($this->accounting_period_model)->include('months');
        } else {
            abort(Response::HTTP_BAD_REQUEST);
        }

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
            'accounting_period_id'     => __('models.accounting_period.name.one'),
            'expense_category_ids'     => __('models.expense.category.name.many'),
            'expense_sub_category_ids' => __('models.expense.sub_category.name.many'),
            'expense_item_ids'         => __('models.expense.item.name.many'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        $accountingPeriod = app(AccountingPeriod::class);
        $expenseCategory = app(ExpenseCategory::class);
        $expenseSubCategory = app(ExpenseSubCategory::class);
        $expenseItem = app(ExpenseItem::class);
        $team = Services::team()->current();

        return [
            'accounting_period_id' => [
                Rule::exists($accountingPeriod->getTable(), $accountingPeriod->getKeyName())
                    ->where($accountingPeriod->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
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
