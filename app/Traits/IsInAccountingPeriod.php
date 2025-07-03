<?php

namespace App\Traits;

use App\Facades\Services;
use App\Models\AccountingPeriod;
use Illuminate\Database\Eloquent\Builder;

trait IsInAccountingPeriod
{
    public static function bootIsInAccountingPeriod(): void {}

    public function initializeIsInAccountingPeriod(): void {}

    public function isInCurrentAccountingPeriod(): bool
    {
        return $this->isInAccountingPeriod(Services::accountingPeriod()->current());
    }

    abstract public function isInAccountingPeriod(AccountingPeriod|int $accountingPeriod): bool;

    abstract public function scopeWhereInAccountingPeriod(Builder $query, AccountingPeriod|int $accountingPeriod): Builder;
}
