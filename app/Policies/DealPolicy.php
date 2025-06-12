<?php

namespace App\Policies;

use App\Models\Deal;
use App\Models\User;

class DealPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Deal $deal): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Deal $deal): bool
    {
        return false;
    }

    public function trash(User $user, Deal $deal): bool
    {
        return false;
    }

    public function restore(User $user, Deal $deal): bool
    {
        return false;
    }

    public function delete(User $user, Deal $deal): bool
    {
        return false;
    }
}
