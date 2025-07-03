<?php

namespace App\Data\Employee\Form;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\Hidden;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class UpdateEmployeeEndsAtRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('employee')]
        public Employee $employee,

        public Carbon $ends_at,
    ) {}

    public static function attributes(): array
    {
        return [
            'ends_at' => __('models.employee.fields.ends_at'),
        ];
    }

    public static function rules(
        ValidationContext $context,

        #[RouteParameter('employee')]
        Employee $employee,
    ): array {
        return [
            'ends_at' => [
                Rule::date()->afterOrEqual($employee->starts_at),
            ],
        ];
    }
}
