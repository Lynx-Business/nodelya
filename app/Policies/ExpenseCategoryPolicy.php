<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class ExpenseCategoryPolicy
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

    public function view(User $user, ExpenseCategory $expenseCategory): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseCategory->team_id)) {
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

    public function update(User $user, ExpenseCategory $expenseCategory): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function select(User $user, ExpenseCategory $expenseCategory): bool
    {
        return $user->hasTeam($expenseCategory->team_id);
    }

    public function trash(User $user, ExpenseCategory $expenseCategory): bool
    {
        if ($expenseCategory->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, ExpenseCategory $expenseCategory): bool
    {
        if (! $expenseCategory->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, ExpenseCategory $expenseCategory): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }
}
