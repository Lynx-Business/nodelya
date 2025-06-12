<?php

namespace App\Policies;

use App\Models\AccountingPeriod;
use App\Models\User;

class AccountingPeriodPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, AccountingPeriod $accountingPeriod): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, AccountingPeriod $accountingPeriod): bool
    {
        return false;
    }

    public function trash(User $user, AccountingPeriod $accountingPeriod): bool
    {
        return false;
    }

    public function restore(User $user, AccountingPeriod $accountingPeriod): bool
    {
        return false;
    }

    public function delete(User $user, AccountingPeriod $accountingPeriod): bool
    {
        return false;
    }
}
