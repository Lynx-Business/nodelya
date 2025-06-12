<?php

namespace App\Policies;

use App\Models\ProjectDepartment;
use App\Models\User;

class ProjectDepartmentPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ProjectDepartment $projectDepartment): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ProjectDepartment $projectDepartment): bool
    {
        return false;
    }

    public function trash(User $user, ProjectDepartment $projectDepartment): bool
    {
        return false;
    }

    public function restore(User $user, ProjectDepartment $projectDepartment): bool
    {
        return false;
    }

    public function delete(User $user, ProjectDepartment $projectDepartment): bool
    {
        return false;
    }
}
