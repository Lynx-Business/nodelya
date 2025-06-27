<?php

namespace App\Data\Expense\Budget;

use App\Data\Expense\Item\ExpenseItemResource;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\AutoWhenLoadedLazy;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseBudgetResource extends Resource
{
    public function __construct(
        public int $id,
        public float $amount,
        public Carbon $starts_at,
        public Carbon $ends_at,
        public Optional|int $expense_item_id,
        public Optional|Carbon|null $deleted_at,
        public Optional|bool $can_view,
        public Optional|bool $can_update,
        public Optional|bool $can_trash,
        public Optional|bool $can_restore,
        public Optional|bool $can_delete,

        #[AutoWhenLoadedLazy('expenseItem')]
        public Lazy|ExpenseItemResource $expense_item,
    ) {}
}
