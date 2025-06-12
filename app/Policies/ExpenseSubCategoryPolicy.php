<?php

namespace App\Policies;

use App\Models\ExpenseSubCategory;
use App\Models\User;

class ExpenseSubCategoryPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        return false;
    }

    public function trash(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        return false;
    }

    public function restore(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        return false;
    }

    public function delete(User $user, ExpenseSubCategory $expenseSubCategory): bool
    {
        return false;
    }
}
