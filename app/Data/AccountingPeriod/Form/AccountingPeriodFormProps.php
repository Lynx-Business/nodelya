<?php

namespace App\Data\AccountingPeriod\Form;

use App\Data\Team\TeamListResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AccountingPeriodFormProps extends Resource
{
    public function __construct(
        public TeamListResource $team,
        public ?AccountingPeriodFormResource $accountingPeriod,
    ) {}
}
