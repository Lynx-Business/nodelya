<?php

namespace App\Data\Deal\Billing\Form;

use App\Attributes\EnumArrayOf;
use App\Data\Client\ClientResource;
use App\Data\Deal\DealResource;
use App\Data\ProjectDepartment\ProjectDepartmentResource;
use App\Enums\Deal\DealScheduleStatus;
use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class BillingDealFormProps extends Resource
{
    public function __construct(
        public ?DealResource $deal,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientResource::class)]
        public Lazy|DataCollection $clients,

        #[AutoInertiaLazy]
        #[DataCollectionOf(DealResource::class)]
        public Lazy|DataCollection $deals,

        #[AutoInertiaLazy]
        #[EnumArrayOf(DealScheduleStatus::class)]
        public Lazy|array $schedule_status,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ProjectDepartmentResource::class)]
        public Lazy|DataCollection $projectDepartments,
    ) {}
}
