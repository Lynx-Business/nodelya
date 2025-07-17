<?php

namespace App\Data\Deal\Billing\Index;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Client\ClientResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\Client;
use App\Models\Deal;
use Illuminate\Database\Eloquent\Builder;
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
    #[DataCollectionOf(ClientResource::class)]
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
            $this->clients_items = ClientResource::collect(
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

    public function toQuery(?Builder $query = null): Builder
    {
        $query = $query ?? Deal::commercial();

        return $query
            ->search($this->q)
            ->when($this->client_ids, fn (Builder $q) => $q->whereIntegerInRaw('client_id', $this->client_ids))
            ->when($this->name, fn (Builder $q) => $q->where('name', 'like', '%'.$this->name.'%'))
            ->when($this->amount, fn (Builder $q) => $q->where('amount_in_cents', Services::conversion()->priceToCents($this->amount)))
            ->when($this->code, fn (Builder $q) => $q->where('code', 'like', '%'.$this->code.'%'))
            ->when($this->trashed, fn (Builder $q) => $q->filterTrashed($this->trashed))
            ->when($this->accounting_period, fn (Builder $q) => $q->whereInAccountingPeriod($this->accounting_period->id));
    }
}
