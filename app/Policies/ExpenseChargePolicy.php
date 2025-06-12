<?php

namespace App\Policies;

use App\Models\ExpenseCharge;
use App\Models\User;

class ExpenseChargePolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ExpenseCharge $expenseCharge): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ExpenseCharge $expenseCharge): bool
    {
        return false;
    }

    public function trash(User $user, ExpenseCharge $expenseCharge): bool
    {
        return false;
    }

    public function restore(User $user, ExpenseCharge $expenseCharge): bool
    {
        return false;
    }

    public function delete(User $user, ExpenseCharge $expenseCharge): bool
    {
        return false;
    }
}
