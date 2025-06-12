<?php

namespace App\Policies;

use App\Models\ExpenseBudget;
use App\Models\User;

class ExpenseBudgetPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ExpenseBudget $expenseBudget): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ExpenseBudget $expenseBudget): bool
    {
        return false;
    }

    public function trash(User $user, ExpenseBudget $expenseBudget): bool
    {
        return false;
    }

    public function restore(User $user, ExpenseBudget $expenseBudget): bool
    {
        return false;
    }

    public function delete(User $user, ExpenseBudget $expenseBudget): bool
    {
        return false;
    }
}
