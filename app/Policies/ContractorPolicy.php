<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\Contractor;
use App\Models\User;

class ContractorPolicy
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

    public function view(User $user, Contractor $contractor): bool
    {
        return $contractor->isSameTeam($user->team_id);
    }

    public function create(User $user): bool
    {
        return $user->is_editor;
    }

    public function update(User $user, Contractor $contractor): bool
    {
        if (! $contractor->isInCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $contractor->isSameTeam($user->team_id);
    }

    public function trash(User $user, Contractor $contractor): bool
    {
        if ($contractor->is_trashed) {
            return false;
        }

        if (! $contractor->isInCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $contractor->isSameTeam($user->team_id);
    }

    public function restore(User $user, Contractor $contractor): bool
    {
        if (! $contractor->is_trashed) {
            return false;
        }

        if (! $contractor->isInCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $contractor->isSameTeam($user->team_id);
    }

    public function delete(User $user, Contractor $contractor): bool
    {
        if (! $contractor->isInCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $contractor->isSameTeam($user->team_id);
    }
}
