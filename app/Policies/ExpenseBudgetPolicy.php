<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\ExpenseBudget;
use App\Models\User;

class ExpenseBudgetPolicy
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

    public function view(User $user, ExpenseBudget $expenseBudget): bool
    {
        return $expenseBudget->isSameTeam($user->team_id);
    }

    public function create(User $user): bool
    {
        return $user->is_editor;
    }

    public function update(User $user, ExpenseBudget $expenseBudget): bool
    {
        if (! $expenseBudget->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseBudget->isSameTeam($user->team_id);
    }

    public function trash(User $user, ExpenseBudget $expenseBudget): bool
    {
        if ($expenseBudget->is_trashed) {
            return false;
        }

        if (! $expenseBudget->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseBudget->isSameTeam($user->team_id);
    }

    public function restore(User $user, ExpenseBudget $expenseBudget): bool
    {
        if (! $expenseBudget->is_trashed) {
            return false;
        }

        if (! $expenseBudget->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseBudget->isSameTeam($user->team_id);
    }

    public function delete(User $user, ExpenseBudget $expenseBudget): bool
    {
        if (! $expenseBudget->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseBudget->isSameTeam($user->team_id);
    }
}
