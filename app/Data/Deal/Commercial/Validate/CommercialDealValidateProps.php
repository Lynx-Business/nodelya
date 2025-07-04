<?php

namespace App\Data\Deal\Commercial\Validate;

use App\Data\Deal\DealListResource;
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

        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,

        public DealListResource $deal,

        public string $reference,
    ) {}
}
