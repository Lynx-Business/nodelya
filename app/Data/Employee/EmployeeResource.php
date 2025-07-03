<?php

namespace App\Data\Employee;

use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Models\Employee;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class EmployeeResource extends Resource
{
    public function __construct(
        public int $id,
        public ?int $project_department_id,
        public string $first_name,
        public string $last_name,
        public string $full_name,
        public string $phone,
        public string $email,
        public Carbon $starts_at,
        public ?Carbon $ends_at,
        public ?Carbon $deleted_at,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,

        public Lazy|ProjectDepartmentResource $project_department,
    ) {}

    public static function fromModel(Employee $employee): static
    {
        return new static(
            id                    : $employee->id,
            project_department_id : $employee->project_department_id,
            first_name            : $employee->first_name,
            last_name             : $employee->last_name,
            full_name             : $employee->full_name,
            phone                 : $employee->phone,
            email                 : $employee->email,
            starts_at             : $employee->starts_at,
            ends_at               : $employee->ends_at,
            deleted_at            : $employee->deleted_at,
            can_view              : Lazy::create(fn () => $employee->can_view),
            can_update            : Lazy::create(fn () => $employee->can_update),
            can_trash             : Lazy::create(fn () => $employee->can_trash),
            can_restore           : Lazy::create(fn () => $employee->can_restore),
            can_delete            : Lazy::create(fn () => $employee->can_delete),
            project_department    : Lazy::whenLoaded('projectDepartment', $employee, fn () => ProjectDepartmentResource::from($employee->projectDepartment)),
        );
    }
}
