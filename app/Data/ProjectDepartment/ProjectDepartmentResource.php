<?php

namespace App\Data\ProjectDepartment;

use App\Models\ProjectDepartment;
use Carbon\Carbon;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ProjectDepartmentResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public ?Carbon $deleted_at,

        public Lazy|bool $can_view,

        public Lazy|bool $can_update,

        public Lazy|bool $can_trash,

        public Lazy|bool $can_restore,

        public Lazy|bool $can_delete,
    ) {}

    public static function fromModel(ProjectDepartment $projectDepartment): static
    {
        return new static(
            id          : $projectDepartment->id,
            name        : $projectDepartment->name,
            deleted_at  : $projectDepartment->deleted_at,
            can_view    : Lazy::create(fn () => $projectDepartment->can_view),
            can_update  : Lazy::create(fn () => $projectDepartment->can_update),
            can_trash   : Lazy::create(fn () => $projectDepartment->can_trash),
            can_restore : Lazy::create(fn () => $projectDepartment->can_restore),
            can_delete  : Lazy::create(fn () => $projectDepartment->can_delete),
        );
    }
}
