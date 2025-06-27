<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class DealPolicy
{
    public function before(User $user): ?bool
    {
        if (! $user->hasPermissionTo(PermissionName::DEAL->value)) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Deal $deal): bool
    {
        return $deal->isSameTeam($user->team_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Deal $deal): bool
    {
        return $deal->isSameTeam($user->team_id);
    }

    public function trash(User $user, Deal $deal): bool
    {
        if ($deal->is_trashed) {
            return false;
        }
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! $deal->isSameTeam($user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, Deal $deal): bool
    {
        if (! $deal->is_trashed) {
            return false;
        }
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! $deal->isSameTeam($user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, Deal $deal): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! $deal->isSameTeam($user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }
}
