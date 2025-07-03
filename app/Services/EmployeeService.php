<?php

namespace App\Services;

use App\Actions\Employee\CreateOrUpdateEmployee;
use App\Actions\Employee\DeleteEmployeeEndsAt;
use App\Actions\Employee\UpdateEmployeeEndsAt;

class EmployeeService
{
    public function __construct(
        public CreateOrUpdateEmployee $createOrUpdate,
        public UpdateEmployeeEndsAt $updateEndsAt,
        public DeleteEmployeeEndsAt $deleteEndsAt,
    ) {}
}
