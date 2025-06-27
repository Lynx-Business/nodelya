<?php

namespace App\Data\Expense\Item;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryResource;
use App\Enums\Expense\ExpenseType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
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
        public int $expense_sub_category_id,
        public string $name,
        public Optional|Carbon|null $deleted_at,
        public Optional|ExpenseType $type,
        public Optional|bool $can_view,
        public Optional|bool $can_update,
        public Optional|bool $can_trash,
        public Optional|bool $can_restore,
        public Optional|bool $can_delete,

        #[AutoWhenLoadedLazy]
        public Lazy|ExpenseCategoryResource $expense_category,

        #[AutoWhenLoadedLazy]
        public Lazy|ExpenseSubCategoryResource|null $expense_sub_category,
    ) {}
}
