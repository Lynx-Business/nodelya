<?php

namespace App\Enums\Deal;

use App\Traits\Enums\Labels;

enum DealScheduleStatus: string
{
    use Labels;

    case PAID = 'paid';
    case INVOICED = 'invoiced';
    case UNCERTAIN = 'uncertain';

    public function label(): string
    {
        return __("enums.deal.schedule_status.{$this->value}");
    }
}
