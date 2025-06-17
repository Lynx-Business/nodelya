<?php

namespace App\Services;

use App\Actions\ProjectDepartment\CreateOrUpdateProjectDepartment;

class ProjectDepartmentService
{
    public function __construct(
        public CreateOrUpdateProjectDepartment $createOrUpdate,
    ) {}
}
