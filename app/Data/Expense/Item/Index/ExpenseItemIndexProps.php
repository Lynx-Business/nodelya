<?php

namespace App\Data\Expense\Item\Index;

use App\Attributes\EnumArrayOf;
use App\Data\Expense\Category\ExpenseCategoryListResource;
use App\Data\Expense\SubCategory\ExpenseSubCategoryListResource;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use App\Enums\Trashed\TrashedFilter;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseItemIndexProps extends Resource
{
    public function __construct(
        public ExpenseItemIndexRequest $request,

        public TeamListResource $team,

        #[AutoInertiaLazy]
        #[EnumArrayOf(ExpenseType::class)]
        public Lazy|array $expenseTypes,

        public ExpenseType $expenseType,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseItemIndexResource::class)]
        public Lazy|PaginatedDataCollection $expenseItems,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseCategoryListResource::class)]
        public Lazy|DataCollection $expenseCategories,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseSubCategoryListResource::class)]
        public Lazy|DataCollection $expenseSubCategories,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashedFilters,
    ) {}
}
