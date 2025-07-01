<?php

namespace App\Data\Expense\SubCategory;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseSubCategory;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseSubCategoryResource extends Resource
{
    public function __construct(
        public int $id,
        public int $expense_category_id,
        public string $name,
        public ?Carbon $deleted_at,

        public Lazy|ExpenseType $type,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|Optional|ExpenseCategoryResource $expense_category,

        #[DataCollectionOf(ExpenseItemResource::class)]
        public Lazy|Optional|DataCollection $expense_items,
    ) {}

    public static function fromModel(ExpenseSubCategory $subCategory): static
    {
        return new static(
            id                  : $subCategory->id,
            expense_category_id : $subCategory->expense_category_id,
            name                : $subCategory->name,
            deleted_at          : $subCategory->deleted_at,
            type                : Lazy::create(fn () => $subCategory->type),
            can_view            : Lazy::create(fn () => $subCategory->can_view),
            can_update          : Lazy::create(fn () => $subCategory->can_update),
            can_trash           : Lazy::create(fn () => $subCategory->can_trash),
            can_restore         : Lazy::create(fn () => $subCategory->can_restore),
            can_delete          : Lazy::create(fn () => $subCategory->can_delete),
            expense_category    : Lazy::whenLoaded('expenseCategory', $subCategory, fn () => ExpenseCategoryResource::from($subCategory->expenseCategory)),
            expense_items       : Lazy::whenLoaded('expenseItems', $subCategory, fn () => ExpenseItemResource::collect($subCategory->expenseItems)),
        );
    }
}
