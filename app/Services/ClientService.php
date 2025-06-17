<?php

namespace App\Services;

use App\Actions\User\Member\CreateOrUpdateMemberUser;
use App\Actions\User\SelectUserTeam;

class ClientService
{
    public function __construct(
        public CreateOrUpdateMemberUser $createOrUpdateMember,
        public SelectUserTeam $selectTeam,
    ) {}
}
