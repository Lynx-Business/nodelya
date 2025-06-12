<?php

namespace App\Policies;

use App\Models\Contractor;
use App\Models\User;

class ContractorPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Contractor $contractor): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Contractor $contractor): bool
    {
        return false;
    }

    public function trash(User $user, Contractor $contractor): bool
    {
        return false;
    }

    public function restore(User $user, Contractor $contractor): bool
    {
        return false;
    }

    public function delete(User $user, Contractor $contractor): bool
    {
        return false;
    }
}
