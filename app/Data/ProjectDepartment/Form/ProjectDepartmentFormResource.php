<?php

namespace App\Data\ProjectDepartment\Form;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProjectDepartmentFormResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
