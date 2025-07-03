<?php

namespace App\Data\Employee\Form;

use App\Data\Employee\EmployeeResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EmployeeFormProps extends Resource
{
    public function __construct(
        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,

        public ?EmployeeResource $employee,
    ) {
        $this->employee?->include('can_update');
    }
}
