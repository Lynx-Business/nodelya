<?php

namespace App\Traits;

use App\Models\ProjectDepartment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToProjectDepartment
{
    public static function bootBelongsToProjectDepartment(): void {}

    public function initializeBelongsToProjectDepartment(): void {}

    public function getProjectDepartmentIdColumn(): string
    {
        return defined(static::class.'::PROJECT_DEPARTMENT_ID') ? static::PROJECT_DEPARTMENT_ID : 'project_department_id';
    }

    public function getQualifiedProjectDepartmentIdColumn(): string
    {
        return $this->qualifyColumn($this->getProjectDepartmentIdColumn());
    }

    public function projectDepartment(): BelongsTo
    {
        return $this->belongsTo(ProjectDepartment::class, $this->getProjectDepartmentIdColumn());
    }

    public function scopeWhereBelongsToProjectDepartment(Builder $query, ProjectDepartment|int $projectDepartment): Builder
    {
        $projectDepartment = is_int($projectDepartment) ? $projectDepartment : $projectDepartment->getKey();

        return $query
            ->where($this->getQualifiedProjectDepartmentIdColumn(), $projectDepartment);
    }
}
