<?php

namespace App\Services;

use App\Actions\Client\CreateOrUpdateClient;

class ClientService
{
    public function __construct(
        public CreateOrUpdateClient $createOrUpdate,
    ) {}
}
