<?php

namespace App\Data\Expense\Budget;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Enums\Expense\ExpenseType;
use App\Models\AccountingPeriod;
use App\Models\ExpenseBudget;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseBudgetResource extends Resource
{
    public function __construct(
        public int $id,

        public int $accounting_period_id,

        public int $expense_item_id,

        #[LiteralTypeScriptType("'contractor' | 'employee'")]
        public ?string $model_type,

        public ?int $model_id,

        public float $amount,

        public ?Carbon $deleted_at,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|ExpenseType $type,

        public Lazy|Carbon $starts_at,

        public Lazy|Carbon $ends_at,

        public Lazy|int $monthly_amount,

        public Lazy|AccountingPeriod $accounting_period,

        public Lazy|Optional|ExpenseItemResource $expense_item,
    ) {}

    public static function fromModel(ExpenseBudget $budget): static
    {
        return new static(
            id                   : $budget->id,
            accounting_period_id : $budget->accounting_period_id,
            expense_item_id      : $budget->expense_item_id,
            model_type           : $budget->model_type,
            model_id             : $budget->model_id,
            amount               : $budget->amount,
            deleted_at           : $budget->deleted_at,
            can_view             : Lazy::create(fn () => $budget->can_view),
            can_update           : Lazy::create(fn () => $budget->can_update),
            can_trash            : Lazy::create(fn () => $budget->can_trash),
            can_restore          : Lazy::create(fn () => $budget->can_restore),
            can_delete           : Lazy::create(fn () => $budget->can_delete),
            type                 : Lazy::create(fn () => $budget->type),
            starts_at            : Lazy::create(fn () => $budget->starts_at),
            ends_at              : Lazy::create(fn () => $budget->ends_at),
            monthly_amount       : Lazy::create(fn () => $budget->monthly_amount),
            accounting_period    : Lazy::whenLoaded('accountingPeriod', $budget, fn () => AccountingPeriodResource::from($budget->accountingPeriod)),
            expense_item         : Lazy::whenLoaded('expenseItem', $budget, fn () => ExpenseItemResource::from($budget->expenseItem)),
        );
    }
}
