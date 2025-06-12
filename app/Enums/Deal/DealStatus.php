<?php

namespace App\Enums\Deal;

use App\Traits\Enums\Labels;

enum DealStatus: string
{
    use Labels;

    case CREATED = 'created';
    case VALIDATED = 'validated';
    case FINISHED = 'finished';

    public function label(): string
    {
        return __("enums.deal.status.{$this->value}");
    }
}
