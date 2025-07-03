<?php

namespace App\Data\Employee;

use App\Facades\Services;
use App\Models\Employee;
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
class EmployeeOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('employee')]
        #[ExcludeWith('ids')]
        public ?int $employee = null,

        #[Min(1)]
        #[RequiredWithout('employee')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'employee' => __('models.employee.name.one'),
            'ids'      => __('models.employee.name.many'),
        ];
    }

    public static function rules(
        ValidationContext $context,
    ): array {
        $employee = app(Employee::class);
        $team = Services::team()->current();

        return [
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists($employee->getTable(), $employee->getKeyName())
                    ->where($employee->getQualifiedTeamIdColumn(), $team->getKey()),
            ],
        ];
    }
}
