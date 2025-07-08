<?php

namespace App\Services;

use App\Actions\Client\CreateOrUpdateClient;
use App\Data\Client\ClientListResource;
use App\Models\Client;

class ClientService
{
    public function __construct(
        public CreateOrUpdateClient $createOrUpdate,
    ) {}

    public function list()
    {
        return ClientListResource::collect(
            Client::query()->orderBy('name')->get(),
        );
    }
}
