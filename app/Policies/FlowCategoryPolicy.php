<?php

namespace App\Policies;

use App\Models\FlowCategory;
use App\Models\User;

class FlowCategoryPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, FlowCategory $flowCategory): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, FlowCategory $flowCategory): bool
    {
        return false;
    }

    public function trash(User $user, FlowCategory $flowCategory): bool
    {
        return false;
    }

    public function restore(User $user, FlowCategory $flowCategory): bool
    {
        return false;
    }

    public function delete(User $user, FlowCategory $flowCategory): bool
    {
        return false;
    }
}
