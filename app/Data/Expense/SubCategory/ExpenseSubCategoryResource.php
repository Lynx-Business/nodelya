<?php

namespace App\Data\Expense\SubCategory;

use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Enums\Expense\ExpenseType;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
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
        public string $name,
        public Optional|Carbon|null $deleted_at,
        public Optional|ExpenseType $type,
        public Optional|bool $can_view,
        public Optional|bool $can_update,
        public Optional|bool $can_trash,
        public Optional|bool $can_restore,
        public Optional|bool $can_delete,
        public Optional|ExpenseCategoryResource $expense_category,

        #[AutoWhenLoadedLazy]
        #[DataCollectionOf(ExpenseItemResource::class)]
        public Lazy|DataCollection $expense_items,
    ) {}
}
