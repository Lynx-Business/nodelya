<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\ExpenseCharge;
use App\Models\User;

class ExpenseChargePolicy
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

    public function view(User $user, ExpenseCharge $expenseCharge): bool
    {
        return $expenseCharge->isSameTeam($user->team_id);
    }

    public function create(User $user): bool
    {
        return $user->is_editor;
    }

    public function update(User $user, ExpenseCharge $expenseCharge): bool
    {
        if (! $expenseCharge->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseCharge->isSameTeam($user->team_id);
    }

    public function trash(User $user, ExpenseCharge $expenseCharge): bool
    {
        if ($expenseCharge->is_trashed) {
            return false;
        }

        if (! $expenseCharge->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseCharge->isSameTeam($user->team_id);
    }

    public function restore(User $user, ExpenseCharge $expenseCharge): bool
    {
        if (! $expenseCharge->is_trashed) {
            return false;
        }

        if (! $expenseCharge->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseCharge->isSameTeam($user->team_id);
    }

    public function delete(User $user, ExpenseCharge $expenseCharge): bool
    {
        if (! $expenseCharge->belongsToCurrentAccountingPeriod()) {
            return false;
        }

        return $user->is_editor && $expenseCharge->isSameTeam($user->team_id);
    }
}
