<?php

namespace App\Policies;

use App\Models\ExpenseSubCategory;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class ExpenseSubCategoryPolicy
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

    public function view(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseSubCategory->team_id)) {
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

    public function update(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseSubCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function trash(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        if ($expenseSubCategory->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseSubCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        if (! $expenseSubCategory->is_trashed) {
            return false;
        }

        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseSubCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }

        if (! $user->hasTeam($expenseSubCategory->team_id)) {
            return false;
        }

        return $user->is_owner;
    }
}
