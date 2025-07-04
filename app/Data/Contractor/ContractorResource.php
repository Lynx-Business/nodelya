<?php

namespace App\Data\Contractor;

use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Models\Contractor;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ContractorResource extends Resource
{
    public function __construct(
        public int $id,
        public ?int $project_department_id,
        public string $first_name,
        public string $last_name,
        public string $full_name,
        public ?string $phone,
        public ?string $email,
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

    public static function fromModel(Contractor $contractor): static
    {
        return new static(
            id                    : $contractor->id,
            project_department_id : $contractor->project_department_id,
            first_name            : $contractor->first_name,
            last_name             : $contractor->last_name,
            full_name             : $contractor->full_name,
            phone                 : $contractor->phone,
            email                 : $contractor->email,
            starts_at             : $contractor->starts_at,
            ends_at               : $contractor->ends_at,
            deleted_at            : $contractor->deleted_at,
            can_view              : Lazy::create(fn () => $contractor->can_view),
            can_update            : Lazy::create(fn () => $contractor->can_update),
            can_trash             : Lazy::create(fn () => $contractor->can_trash),
            can_restore           : Lazy::create(fn () => $contractor->can_restore),
            can_delete            : Lazy::create(fn () => $contractor->can_delete),
            project_department    : Lazy::whenLoaded('projectDepartment', $contractor, fn () => ProjectDepartmentResource::from($contractor->projectDepartment)),
        );
    }
}
