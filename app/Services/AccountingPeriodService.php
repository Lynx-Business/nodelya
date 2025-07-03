<?php

namespace App\Services;

use App\Actions\AccountingPeriod\CreateOrUpdateAccountingPeriod;
use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Models\AccountingPeriod;
use Closure;

class AccountingPeriodService
{
    protected ?AccountingPeriod $current;

    public function __construct(
        public CreateOrUpdateAccountingPeriod $createOrUpdate,
    ) {}

    public function currentId(): ?int
    {
        return $this->current()?->id;
    }

    public function current(): ?AccountingPeriod
    {
        return $this->current ??= AccountingPeriod::query()
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->first();
    }

    /**
     * @param  ?(Closure(\Illuminate\Database\Eloquent\Builder<AccountingPeriod> $query): Builder)  $callback
     */
    public function list(?Closure $callback = null)
    {
        return AccountingPeriodResource::collect(
            value($callback ?? AccountingPeriod::query(), AccountingPeriod::query())
                ->orderByDesc('starts_at')
                ->get(),
        );
    }
}
