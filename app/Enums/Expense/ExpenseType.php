<?php

namespace App\Enums\Expense;

use App\Models\Contractor;
use App\Models\Employee;
use App\Traits\Enums\Labels;
use Illuminate\Database\Eloquent\Relations\Relation;

enum ExpenseType: string
{
    use Labels;

    case GENERAL = 'general';
    case EMPLOYEE = 'employee';
    case CONTRACTOR = 'contractor';

    public function label(): string
    {
        return __("enums.expense.type.{$this->value}");
    }

    public static function fromMorphType(?string $morphType = null): static
    {
        return match ($morphType) {
            Relation::getMorphAlias(Contractor::class) => self::CONTRACTOR,
            Relation::getMorphAlias(Employee::class)   => self::EMPLOYEE,
            default                                    => self::GENERAL,
        };
    }
}
