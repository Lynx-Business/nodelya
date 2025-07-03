<?php

namespace App\Services;

use App\Actions\ProjectDepartment\CreateOrUpdateProjectDepartment;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Models\ProjectDepartment;
use Closure;

class ProjectDepartmentService
{
    public function __construct(
        public CreateOrUpdateProjectDepartment $createOrUpdate,
    ) {}

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<ProjectDepartment> $query): Builder)  $callback
     */
    public function list(?Closure $callback = null)
    {
        return ProjectDepartmentResource::collect(
            value($callback ?? ProjectDepartment::query(), ProjectDepartment::query())
                ->orderBy('name')
                ->get(),
        );
    }
}
