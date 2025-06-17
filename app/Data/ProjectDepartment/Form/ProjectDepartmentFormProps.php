<?php

namespace App\Data\ProjectDepartment\Form;

use App\Data\Team\TeamListResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProjectDepartmentFormProps extends Resource
{
    public function __construct(
        public TeamListResource $team,
        public ?ProjectDepartmentFormResource $projectDepartment,
    ) {}
}
