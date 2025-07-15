<?php

namespace App\Traits;

use App\Facades\Services;
use App\Models\AccountingPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToAccountingPeriod
{
    public static function bootBelongsToAccountingPeriod(): void {}

    public function initializeBelongsToAccountingPeriod(): void {}

    public function getAccountingPeriodIdColumn(): string
    {
        return defined(static::class.'::ACCOUNTING_PERIOD_ID') ? static::ACCOUNTING_PERIOD_ID : 'accounting_period_id';
    }

    public function getQualifiedAccountingPeriodIdColumn(): string
    {
        return $this->qualifyColumn($this->getAccountingPeriodIdColumn());
    }

    public function accountingPeriod(): BelongsTo
    {
        return $this->belongsTo(AccountingPeriod::class, $this->getAccountingPeriodIdColumn());
    }

    public function scopeWhereBelongsToAccountingPeriod(Builder $query, AccountingPeriod|int $accountingPeriod): Builder
    {
        $accountingPeriod = is_int($accountingPeriod) ? $accountingPeriod : $accountingPeriod->getKey();

        return $query
            ->where($this->getQualifiedAccountingPeriodIdColumn(), $accountingPeriod);
    }

    public function belongsToCurrentAccountingPeriod(): bool
    {
        return Services::accountingPeriod()->currentId() === $this->getAttribute($this->getAccountingPeriodIdColumn());
    }
}
