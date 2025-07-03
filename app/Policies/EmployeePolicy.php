<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function before(User $user): ?bool
    {
        if (! $user->hasPermissionTo(PermissionName::EXPENSES)) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Employee $employee): bool
    {
        return $employee->isSameTeam($user->team_id);
    }

    public function create(User $user): bool
    {
        return $user->is_editor;
    }

    public function update(User $user, Employee $employee): bool
    {
        return $user->is_editor && $employee->isSameTeam($user->team_id);
    }

    public function trash(User $user, Employee $employee): bool
    {
        if ($employee->is_trashed) {
            return false;
        }

        return $user->is_editor && $employee->isSameTeam($user->team_id);
    }

    public function restore(User $user, Employee $employee): bool
    {
        if (! $employee->is_trashed) {
            return false;
        }

        return $user->is_editor && $employee->isSameTeam($user->team_id);
    }

    public function delete(User $user, Employee $employee): bool
    {
        return $user->is_editor && $employee->isSameTeam($user->team_id);
    }
}
