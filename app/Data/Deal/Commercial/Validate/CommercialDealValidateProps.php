<?php

namespace App\Data\Deal\Commercial\Validate;

use App\Data\Client\ClientResource;
use App\Data\Contractor\ContractorResource;
use App\Data\Deal\DealResource;
use App\Data\Expense\Item\ExpenseItemResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealValidateProps extends Resource
{
    public function __construct(

        public DealResource $deal,

        public string $reference,

        public ?ClientResource $client,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ContractorResource::class)]
        public Lazy|DataCollection $contractors,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ExpenseItemResource::class)]
        public Lazy|DataCollection $expenseItems,

    ) {}
}
