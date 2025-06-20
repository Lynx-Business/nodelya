<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\Client;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class ClientPolicy
{
    public function before(User $user): ?bool
    {

        if (! $user->hasPermissionTo(PermissionName::CLIENT->value)) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Client $client): bool
    {
        return $client->isSameTeam($user->team_id);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Client $client): bool
    {
        return $client->isSameTeam($user->team_id);
    }

    public function trash(User $user, Client $client): bool
    {

        if ($client->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $client->isSameTeam($user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, Client $client): bool
    {

        if (! $client->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $client->isSameTeam($user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, Client $client): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $client->isSameTeam($user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }
}
