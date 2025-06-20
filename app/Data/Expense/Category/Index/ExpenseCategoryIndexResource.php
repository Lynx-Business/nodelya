<?php

namespace App\Data\Expense\Category\Index;

use App\Enums\Expense\ExpenseType;
use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseCategoryIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public string $name,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
        public ?Carbon $deleted_at,
    ) {}
}
