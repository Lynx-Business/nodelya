<?php

namespace App\Policies;

use App\Models\ExpenseCategory;
use App\Models\User;

class ExpenseCategoryPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ExpenseCategory $expenseCategory): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ExpenseCategory $expenseCategory): bool
    {
        return false;
    }

    public function trash(User $user, ExpenseCategory $expenseCategory): bool
    {
        return false;
    }

    public function restore(User $user, ExpenseCategory $expenseCategory): bool
    {
        return false;
    }

    public function delete(User $user, ExpenseCategory $expenseCategory): bool
    {
        return false;
    }
}
