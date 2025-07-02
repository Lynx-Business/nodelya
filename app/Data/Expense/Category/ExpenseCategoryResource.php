<?php

namespace App\Data\Expense\Category;

use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseCategoryResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public string $name,
        public ?Carbon $deleted_at,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        #[DataCollectionOf(ExpenseSubCategoryResource::class)]
        public Lazy|Optional|DataCollection $expense_sub_categories,
    ) {}

    public static function fromModel(ExpenseCategory $category): static
    {
        return new static(
            id                     : $category->id,
            type                   : $category->type,
            name                   : $category->name,
            deleted_at             : $category->deleted_at,
            can_view               : Lazy::create(fn () => $category->can_view),
            can_update             : Lazy::create(fn () => $category->can_update),
            can_trash              : Lazy::create(fn () => $category->can_trash),
            can_restore            : Lazy::create(fn () => $category->can_restore),
            can_delete             : Lazy::create(fn () => $category->can_delete),
            expense_sub_categories : Lazy::whenLoaded('expenseItems', $category, fn () => ExpenseSubCategoryResource::collect($category->expenseSubCategories)),
        );
    }
}
