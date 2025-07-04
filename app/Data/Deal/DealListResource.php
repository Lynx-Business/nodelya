<?php

namespace App\Data\Deal;

use App\Data\ProjectDepartment\ProjectDepartmentResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DealListResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public float $amount,
        public ?ProjectDepartmentResource $project_department,
    ) {}
}
