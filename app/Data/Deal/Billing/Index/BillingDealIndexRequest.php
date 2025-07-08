<?php

namespace App\Data\Deal\Billing\Index;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Client\ClientListResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\Client;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class BillingDealIndexRequest extends Data
{
    #[Computed]
    public ?AccountingPeriodResource $accounting_period;

    #[Computed]
    #[DataCollectionOf(ClientListResource::class)]
    public ?DataCollection $clients_items;

    public function __construct(
        public ?string $q = null,
        public ?int $page = null,
        public ?int $per_page = null,
        public string $sort_by = 'id',
        public string $sort_direction = 'desc',
        public ?int $accounting_period_id = null,
        public ?string $name = null,
        public ?float $amount = null,
        public ?int $success_rate = null,
        public ?string $code = null,

        /** @var null|array<int> $client_ids */
        public ?array $client_ids = null,
        public ?TrashedFilter $trashed = null,
    ) {

        if ($accounting_period_id) {
            $period = AccountingPeriod::find($accounting_period_id);
        } else {
            $period = Services::accountingPeriod()->current();
            $this->accounting_period_id = Services::accountingPeriod()->currentId();
        }

        if ($period) {
            $this->accounting_period = AccountingPeriodResource::from($period);
        }

        if ($client_ids) {
            $this->clients_items = ClientListResource::collect(
                Client::query()
                    ->whereIntegerInRaw('id', $client_ids)
                    ->get(),
                DataCollection::class,
            );
        }
    }

    public static function rules(ValidationContext $context): array
    {
        $accountingPeriod = app(AccountingPeriod::class);
        $team = Services::team()->current();

        return [
            'accounting_period_id' => [
                Rule::exists($accountingPeriod->getTable(), $accountingPeriod->getKeyName())
                    ->where($accountingPeriod->getQualifiedTeamIdColumn(), $team?->getKey()),
            ],
        ];
    }
}
