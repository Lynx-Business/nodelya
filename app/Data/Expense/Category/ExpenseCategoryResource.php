<?php

namespace App\Data\Expense\Category;

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
class ExpenseCategoryResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public string $name,
        public Optional|Carbon|null $deleted_at,
        public Optional|bool $can_view,
        public Optional|bool $can_update,
        public Optional|bool $can_trash,
        public Optional|bool $can_restore,
        public Optional|bool $can_delete,

        #[AutoWhenLoadedLazy]
        #[DataCollectionOf(ExpenseCategoryResource::class)]
        public Lazy|DataCollection $expense_sub_categories,
    ) {}
}
