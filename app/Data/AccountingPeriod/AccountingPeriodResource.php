<?php

namespace App\Data\AccountingPeriod;

use App\Models\AccountingPeriod;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountingPeriodResource extends Resource
{
    public function __construct(
        public int $id,

        public string $label,

        public Carbon $starts_at,

        public Carbon $ends_at,

        public ?Carbon $deleted_at,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|int $duration_in_months,

        /** @var Lazy|array<Carbon> */
        public Lazy|array $months,
    ) {}

    public static function fromModel(AccountingPeriod $accountingPeriod): static
    {
        return new static(
            id                 : $accountingPeriod->id,
            label              : $accountingPeriod->label,
            starts_at          : $accountingPeriod->starts_at,
            ends_at            : $accountingPeriod->ends_at,
            deleted_at         : $accountingPeriod->deleted_at,
            can_view           : Lazy::create(fn () => $accountingPeriod->can_view),
            can_update         : Lazy::create(fn () => $accountingPeriod->can_update),
            can_trash          : Lazy::create(fn () => $accountingPeriod->can_trash),
            can_restore        : Lazy::create(fn () => $accountingPeriod->can_restore),
            can_delete         : Lazy::create(fn () => $accountingPeriod->can_delete),
            duration_in_months : Lazy::create(fn () => $accountingPeriod->duration_in_months),
            months             : Lazy::create(fn () => $accountingPeriod->months),
        );
    }
}
