<?php

namespace App\Services;

use App\Actions\Employee\CreateOrUpdateEmployee;

class EmployeeService
{
    public function __construct(
        public CreateOrUpdateEmployee $createOrUpdate,
    ) {}
}
