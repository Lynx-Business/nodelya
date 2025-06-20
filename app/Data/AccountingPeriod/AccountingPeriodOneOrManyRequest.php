<?php

namespace App\Data\AccountingPeriod;

use App\Models\AccountingPeriod;
use App\Models\Team;
use Illuminate\Container\Attributes\RouteParameter;
use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\ExcludeWith;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class AccountingPeriodOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('accountingPeriod')]
        #[ExcludeWith('ids')]
        public ?int $accounting_period = null,

        #[Min(1)]
        #[RequiredWithout('accountingPeriod')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'accounting_period' => __('models.accounting_period.name.one'),
            'ids'               => __('models.accounting_period.name.many'),
        ];
    }

    public static function rules(ValidationContext $context, #[RouteParameter('team') ] Team $team): array
    {
        $model = app(AccountingPeriod::class);

        return [
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists($model->getTable(), $model->getKeyName())
                    ->where($model->getQualifiedTeamIdColumn(), $team->getKey()),
            ],
        ];
    }
}
