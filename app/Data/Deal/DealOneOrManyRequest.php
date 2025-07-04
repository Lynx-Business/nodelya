<?php

namespace App\Data\Deal;

use App\Models\Deal;
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
class DealOneOrManyRequest extends Data
{
    public function __construct(

        #[FromRouteParameter('deal')]
        #[ExcludeWith('ids')]
        public ?int $deal = null,

        #[Min(1)]
        #[RequiredWithout('deal')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'deal' => __('models.commercial_deal.name.one'),
            'ids'  => __('models.commercial_deal.name.many'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        $deal = app(Deal::class);

        return [
            'ids.*' => ['integer', 'distinct', Rule::exists($deal->getTable(), $deal->getKeyName())],
        ];
    }
}
