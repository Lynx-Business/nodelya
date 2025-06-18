<?php

namespace App\Services;

use App\Actions\Client\CreateOrUpdateClient;
use App\Actions\User\SelectUserTeam;

class ClientService
{
    public function __construct(
        public CreateOrUpdateClient $createOrUpdateClient,
        public SelectUserTeam $selectTeam,
    ) {}
}
