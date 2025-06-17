<?php

namespace App\Data\ProjectDepartment;

use App\Models\ProjectDepartment;
use App\Models\Team;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\ExcludeWith;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ProjectDepartmentOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('projectDepartment')]
        #[ExcludeWith('ids')]
        public ?int $project_department = null,

        #[Min(1)]
        #[RequiredWithout('projectDepartment')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'project_department' => __('models.project_departments.name.one'),
            'ids'                => __('models.project_departments.name.many'),
        ];
    }

    public static function rules(ValidationContext $context, #[RouteParameter('team') ] Team $team): array
    {
        $model = app(ProjectDepartment::class);

        return [
            'ids.*' => ['integer', 'distinct', Rule::exists($model->getTable(), $model->getKeyName())->where($model->getQualifiedTeamIdColumn(), $team->getKey())],
        ];
    }
}
