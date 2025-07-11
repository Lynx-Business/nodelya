<?php

namespace App\Services;

use App\Actions\Client\CreateOrUpdateClient;
use App\Data\Client\ClientResource;
use App\Models\Client;

class ClientService
{
    public function __construct(
        public CreateOrUpdateClient $createOrUpdate,
    ) {}

    public function list()
    {
        return ClientResource::collect(
            Client::query()->orderBy('name')->get(),
        );
    }
}
