<?php

namespace App\Data\Expense\Charge;

use App\Data\Expense\Item\ExpenseItemResource;
use App\Enums\Expense\ExpenseType;
use App\Models\ExpenseCharge;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseChargeResource extends Resource
{
    public function __construct(
        public int $id,
        public float $amount,
        public Carbon $charged_at,
        public int $expense_item_id,
        public ?Carbon $deleted_at,

        public Lazy|ExpenseType $type,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|ExpenseItemResource $expense_item,
    ) {}

    public static function fromModel(ExpenseCharge $charge): static
    {
        return new static(
            id              : $charge->id,
            amount          : $charge->amount,
            charged_at      : $charge->charged_at,
            expense_item_id : $charge->expense_item_id,
            deleted_at      : $charge->deleted_at,
            type            : Lazy::create(fn () => $charge->type),
            can_view        : Lazy::create(fn () => $charge->can_view),
            can_update      : Lazy::create(fn () => $charge->can_update),
            can_trash       : Lazy::create(fn () => $charge->can_trash),
            can_restore     : Lazy::create(fn () => $charge->can_restore),
            can_delete      : Lazy::create(fn () => $charge->can_delete),
            expense_item    : Lazy::whenLoaded('expenseItem', $charge, fn () => ExpenseItemResource::from($charge->expenseItem)),
        );
    }
}
