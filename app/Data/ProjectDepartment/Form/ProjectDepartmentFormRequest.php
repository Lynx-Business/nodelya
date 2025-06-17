<?php

namespace App\Data\ProjectDepartment\Form;

use App\Models\ProjectDepartment;
use App\Models\Team;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ProjectDepartmentFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('team')]
        public Team $team,

        #[Hidden]
        #[FromRouteParameter('projectDepartment')]
        public ?ProjectDepartment $project_department,

        public string $name,
    ) {}

    public static function attributes(): array
    {
        return [
            'name' => __('models.project_department.fields.name'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            //
        ];
    }
}
