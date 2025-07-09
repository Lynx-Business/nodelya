<?php

namespace App\Services;

use App\Actions\Deal\Commercial\CreateOrUpdateCommercialDeal;

class CommercialDealService
{
    public function __construct(
        public CreateOrUpdateCommercialDeal $createOrUpdate,
    ) {}
}
