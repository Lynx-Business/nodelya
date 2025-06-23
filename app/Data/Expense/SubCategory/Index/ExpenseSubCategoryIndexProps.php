<?php

namespace App\Data\Expense\SubCategory\Index;

use App\Attributes\EnumArrayOf;
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
class ExpenseSubCategoryIndexProps extends Resource
{
    public function __construct(
        public ExpenseSubCategoryIndexRequest $request,

        public TeamListResource $team,

        #[AutoInertiaLazy]
        #[EnumArrayOf(ExpenseType::class)]
        public Lazy|array $expenseTypes,

        public ExpenseType $expenseType,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseSubCategoryIndexResource::class)]
        public Lazy|PaginatedDataCollection $expenseSubCategories,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashed_filters,
    ) {}
}
