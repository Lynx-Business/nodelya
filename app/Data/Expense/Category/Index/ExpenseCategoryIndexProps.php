<?php

namespace App\Data\Expense\Category\Index;

use App\Attributes\EnumArrayOf;
use App\Data\Expense\Category\ExpenseCategoryResource;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseCategoryIndexProps extends Resource
{
    public function __construct(
        public ExpenseCategoryIndexRequest $request,

        public TeamListResource $team,

        #[AutoInertiaLazy]
        #[EnumArrayOf(ExpenseType::class)]
        public Lazy|array $expenseTypes,

        public ExpenseType $expenseType,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseCategoryResource::class)]
        public Lazy|PaginatedDataCollection $expenseCategories,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashedFilters,
    ) {}
}
