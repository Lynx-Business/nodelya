<?php

namespace App\Data\Expense\Budget;

use App\Data\Expense\Item\ExpenseItemResource;
use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseBudget;
use Carbon\Carbon;
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
        public int $expense_item_id,
        public ?Carbon $deleted_at,

        public Lazy|ExpenseType $type,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|Optional|ExpenseItemResource $expense_item,
    ) {}

    public static function fromModel(ExpenseBudget $budget): static
    {
        return new static(
            id              : $budget->id,
            amount          : $budget->amount,
            starts_at       : $budget->starts_at,
            ends_at         : $budget->ends_at,
            expense_item_id : $budget->expense_item_id,
            deleted_at      : $budget->deleted_at,
            type            : Lazy::create(fn () => $budget->type),
            can_view        : Lazy::create(fn () => $budget->can_view),
            can_update      : Lazy::create(fn () => $budget->can_update),
            can_trash       : Lazy::create(fn () => $budget->can_trash),
            can_restore     : Lazy::create(fn () => $budget->can_restore),
            can_delete      : Lazy::create(fn () => $budget->can_delete),
            expense_item    : Lazy::whenLoaded('expenseItem', $budget, fn () => ExpenseItemResource::from($budget->expenseItem)),
        );
    }
}
