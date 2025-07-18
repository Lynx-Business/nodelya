<?php

namespace App\Data\Flow\Index;

use App\Data\AccountingPeriod\AccountingPeriodResource;
use App\Data\Client\ClientResource;
use App\Enums\Trashed\TrashedFilter;
use App\Facades\Services;
use App\Models\AccountingPeriod;
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
class FlowIndexRequest extends Data
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

        public ?TrashedFilter $trashed = null,
    ) {

        if ($accounting_period_id) {
            $period = AccountingPeriod::find($accounting_period_id);
        } else {
            $period = Services::accountingPeriod()->current();
            $this->accounting_period_id = Services::accountingPeriod()->currentId();
        }

        if ($period) {
            $this->accounting_period = AccountingPeriodResource::from($period)->include('months');
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
