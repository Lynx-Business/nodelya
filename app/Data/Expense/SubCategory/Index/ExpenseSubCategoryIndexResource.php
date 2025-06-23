<?php

namespace App\Data\Expense\SubCategory\Index;

use App\Data\Expense\Category\ExpenseCategoryListResource;
use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseSubCategoryIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseCategoryListResource $expense_category,
        public string $name,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
        public ?Carbon $deleted_at,
    ) {}
}
