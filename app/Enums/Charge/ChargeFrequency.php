<?php

namespace App\Enums\Charge;

use App\Traits\Enums\Labels;

enum ChargeFrequency: string
{
    use Labels;

    case MONTH = 'month';
    case SEMESTER = 'semester';
    case QUARTER = 'quarter';

    public function label(): string
    {
        return __("enums.charge.frequency.{$this->value}");
    }
}
