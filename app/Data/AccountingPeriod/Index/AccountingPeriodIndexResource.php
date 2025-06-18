<?php

namespace App\Data\AccountingPeriod\Index;

use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountingPeriodIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public string $label,
        public Carbon $starts_at,
        public Carbon $ends_at,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
        public ?Carbon $deleted_at,
    ) {}
}
