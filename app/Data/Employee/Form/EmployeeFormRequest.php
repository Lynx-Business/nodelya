<?php

namespace App\Data\Employee\Form;

use App\Models\Employee;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class EmployeeFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('employee')]
        public ?Employee $employee,

        public int $project_department_id,

        public string $first_name,

        public string $last_name,

        public string $email,

        public ?string $phone,

        public Carbon $starts_at,
    ) {}

    public static function attributes(): array
    {
        return [
            'project_department_id' => __('models.project_department_id.name.one'),
            'first_name'            => __('models.employee.fields.first_name'),
            'last_name'             => __('models.employee.fields.last_name'),
            'email'                 => __('models.employee.fields.email'),
            'phone'                 => __('models.employee.fields.phone'),
            'starts_at'             => __('models.employee.fields.starts_at'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            //
        ];
    }
}
