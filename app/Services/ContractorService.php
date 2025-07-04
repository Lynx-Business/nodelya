<?php

namespace App\Services;

use App\Actions\Contractor\CreateOrUpdateContractor;
use App\Actions\Contractor\DeleteContractorEndsAt;
use App\Actions\Contractor\UpdateContractorEndsAt;

class ContractorService
{
    public function __construct(
        public CreateOrUpdateContractor $createOrUpdate,
        public UpdateContractorEndsAt $updateEndsAt,
        public DeleteContractorEndsAt $deleteEndsAt,
    ) {}
}
