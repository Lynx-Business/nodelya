<?php

namespace App\Data\Contractor\Form;

use App\Data\Contractor\ContractorResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ContractorFormProps extends Resource
{
    public function __construct(
        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,

        public ?ContractorResource $contractor,
    ) {
        $this->contractor?->include('can_update');
    }
}
