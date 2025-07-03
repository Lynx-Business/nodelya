<?php

namespace App\Policies;

use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class AccountingPeriodPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        return $user->is_owner;
    }

    public function view(User $user, AccountingPeriod $accountingPeriod): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($accountingPeriod->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function create(User $user): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        return $user->is_owner;
    }

    public function update(User $user, AccountingPeriod $accountingPeriod): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($accountingPeriod->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function trash(User $user, AccountingPeriod $accountingPeriod): bool
    {
        if ($accountingPeriod->is_trashed) {
            return false;
        }

        if ($accountingPeriod->id == Services::accountingPeriod()->currentId()) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($accountingPeriod->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, AccountingPeriod $accountingPeriod): bool
    {
        if (! $accountingPeriod->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($accountingPeriod->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, AccountingPeriod $accountingPeriod): bool
    {
        if ($accountingPeriod->id == Services::accountingPeriod()->currentId()) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($accountingPeriod->team_id)) {
            return false;
        }

        return $user->is_owner;
    }
}
