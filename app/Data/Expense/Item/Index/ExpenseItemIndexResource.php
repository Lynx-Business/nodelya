<?php

namespace App\Data\Expense\Item\Index;

use App\Data\Expense\Category\ExpenseCategoryListResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryListResource;
use App\Enums\Expense\ExpenseType;
use Carbon\Carbon;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseItemIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public ExpenseType $type,
        public ExpenseCategoryListResource $expense_category,
        public ?ExpenseSubCategoryListResource $expense_sub_category,
        public string $name,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
        public ?Carbon $deleted_at,
    ) {}
}
