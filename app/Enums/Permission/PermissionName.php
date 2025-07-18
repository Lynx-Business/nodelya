<?php

namespace App\Enums\Permission;

use App\Traits\Enums\Labels;

enum PermissionName: string
{
    use Labels;

    case CLIENT = 'client';
    case EXPENSES = 'expenses';
    case DEAL = 'deal';
    case COMMENT = 'comment';
    case TREASURY = 'treasury';

    public function label(): string
    {
        return __("enums.permission.name.{$this->value}");
    }
}
