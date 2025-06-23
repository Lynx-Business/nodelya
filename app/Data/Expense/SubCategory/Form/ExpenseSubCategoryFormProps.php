<?php

namespace App\Data\Expense\SubCategory\Form;

use App\Data\Expense\Category\ExpenseCategoryListResource;
use App\Data\Team\TeamListResource;
use App\Enums\Expense\ExpenseType;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ExpenseSubCategoryFormProps extends Resource
{
    public function __construct(
        public TeamListResource $team,

        public ExpenseType $expenseType,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseCategoryListResource::class)]
        public Lazy|DataCollection $expenseCategories,

        public ?ExpenseSubCategoryFormResource $expenseSubCategory,
    ) {}
}
