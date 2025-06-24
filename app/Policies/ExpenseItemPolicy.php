<?php

namespace App\Policies;

use App\Models\ExpenseItem;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class ExpenseItemPolicy
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

    public function view(User $user, ExpenseItem $expenseItem): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseItem->team_id)) {
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

    public function update(User $user, ExpenseItem $expenseItem): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseItem->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function trash(User $user, ExpenseItem $expenseItem): bool
    {
        if ($expenseItem->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseItem->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, ExpenseItem $expenseItem): bool
    {
        if (! $expenseItem->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseItem->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, ExpenseItem $expenseItem): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseItem->team_id)) {
            return false;
        }

        return $user->is_owner;
    }
}
