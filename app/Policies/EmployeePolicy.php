<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;

class EmployeePolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Employee $employee): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Employee $employee): bool
    {
        return false;
    }

    public function trash(User $user, Employee $employee): bool
    {
        return false;
    }

    public function restore(User $user, Employee $employee): bool
    {
        return false;
    }

    public function delete(User $user, Employee $employee): bool
    {
        return false;
    }
}
