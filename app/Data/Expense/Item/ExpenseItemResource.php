<?php

namespace App\Data\Expense\Item;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseItem;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseItemResource extends Resource
{
    public function __construct(
        public int $id,
        public int $expense_category_id,
        public ?int $expense_sub_category_id,
        public string $name,
        public ?Carbon $deleted_at,

        public Lazy|ExpenseType $type,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|Optional|ExpenseCategoryResource $expense_category,

        public Lazy|Optional|ExpenseSubCategoryResource $expense_sub_category,
    ) {}

    public static function fromModel(ExpenseItem $item): static
    {
        return new static(
            id                      : $item->id,
            expense_category_id     : $item->expense_category_id,
            expense_sub_category_id : $item->expense_sub_category_id,
            name                    : $item->name,
            deleted_at              : $item->deleted_at,
            type                    : Lazy::create(fn () => $item->type),
            can_view                : Lazy::create(fn () => $item->can_view),
            can_update              : Lazy::create(fn () => $item->can_update),
            can_trash               : Lazy::create(fn () => $item->can_trash),
            can_restore             : Lazy::create(fn () => $item->can_restore),
            can_delete              : Lazy::create(fn () => $item->can_delete),
            expense_category        : Lazy::whenLoaded('expenseCategory', $item, fn () => ExpenseCategoryResource::from($item->expenseCategory)),
            expense_sub_category    : Lazy::whenLoaded('expenseSubCategory', $item, fn () => ExpenseSubCategoryResource::from($item->expenseSubCategory)),
        );
    }
}
