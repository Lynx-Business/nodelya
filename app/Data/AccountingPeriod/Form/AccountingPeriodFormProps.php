<?php

namespace App\Data\AccountingPeriod\Form;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Team\TeamListResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountingPeriodFormProps extends Resource
{
    public function __construct(
        public TeamListResource $team,
        public ?AccountingPeriodResource $accountingPeriod,
    ) {}
}
