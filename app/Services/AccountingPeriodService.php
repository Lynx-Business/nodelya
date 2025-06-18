<?php

namespace App\Services;

use App\Actions\AccountingPeriod\CreateOrUpdateAccountingPeriod;

class AccountingPeriodService
{
    public function __construct(
        public CreateOrUpdateAccountingPeriod $createOrUpdate,
    ) {}
}
