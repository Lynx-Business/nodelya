<?php

namespace App\Services;

use App\Actions\Deal\Billing\UpdateBillingDeal;

class BillingDealService
{
    public function __construct(
        public UpdateBillingDeal $update,
    ) {}
}
