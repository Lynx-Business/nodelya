<?php

namespace App\Data\Expense\Charge;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Contractor\ContractorResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Enums\Expense\ExpenseType;
use App\Models\AccountingPeriod;
use App\Models\Contractor;
use App\Models\ExpenseCharge;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\LiteralTypeScriptType;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseChargeResource extends Resource
{
    public function __construct(
        public int $id,

        public int $accounting_period_id,

        public int $expense_item_id,

        #[LiteralTypeScriptType("'contractor' | 'employee'")]
        public ?string $model_type,

        public ?int $model_id,

        public ?int $deal_id,

        public float $amount,

        public Carbon $charged_at,

        public ?Carbon $deleted_at,

        public Lazy|ExpenseType $type,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|AccountingPeriod $accounting_period,

        public Lazy|ExpenseItemResource $expense_item,

        public Lazy|null|ContractorResource $contractor,
    ) {}

    public static function fromModel(ExpenseCharge $charge): static
    {
        return new static(
            id                   : $charge->id,
            accounting_period_id : $charge->accounting_period_id,
            expense_item_id      : $charge->expense_item_id,
            model_type           : $charge->model_type,
            model_id             : $charge->model_id,
            deal_id              : $charge->deal_id,
            amount               : $charge->amount,
            charged_at           : $charge->charged_at,
            deleted_at           : $charge->deleted_at,
            type                 : Lazy::create(fn () => $charge->type),
            can_view             : Lazy::create(fn () => $charge->can_view),
            can_update           : Lazy::create(fn () => $charge->can_update),
            can_trash            : Lazy::create(fn () => $charge->can_trash),
            can_restore          : Lazy::create(fn () => $charge->can_restore),
            can_delete           : Lazy::create(fn () => $charge->can_delete),
            accounting_period    : Lazy::whenLoaded('accountingPeriod', $charge, fn () => AccountingPeriodResource::from($charge->accountingPeriod)),
            contractor           : Lazy::whenLoaded('model', $charge, fn () => $charge->model instanceof Contractor ? ContractorResource::from($charge->model) : null),
            expense_item         : Lazy::whenLoaded('expenseItem', $charge, fn () => ExpenseItemResource::from($charge->expenseItem)),
        );
    }
}
