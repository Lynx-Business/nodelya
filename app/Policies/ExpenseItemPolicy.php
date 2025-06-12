<?php

namespace App\Policies;

use App\Models\ExpenseItem;
use App\Models\User;

class ExpenseItemPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ExpenseItem $expenseItem): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ExpenseItem $expenseItem): bool
    {
        return false;
    }

    public function trash(User $user, ExpenseItem $expenseItem): bool
    {
        return false;
    }

    public function restore(User $user, ExpenseItem $expenseItem): bool
    {
        return false;
    }

    public function delete(User $user, ExpenseItem $expenseItem): bool
    {
        return false;
    }
}
