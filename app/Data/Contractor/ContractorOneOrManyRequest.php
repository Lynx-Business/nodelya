<?php

namespace App\Data\Contractor;

use App\Facades\Services;
use App\Models\Contractor;
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
class ContractorOneOrManyRequest extends Data
{
    public function __construct(
        #[FromRouteParameter('contractor')]
        #[ExcludeWith('ids')]
        public ?int $contractor = null,

        #[Min(1)]
        #[RequiredWithout('contractor')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'contractor' => __('models.contractor.name.one'),
            'ids'        => __('models.contractor.name.many'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        $contractor = app(Contractor::class);
        $team = Services::team()->current();

        return [
            'ids.*' => [
                'integer',
                'distinct',
                Rule::exists($contractor->getTable(), $contractor->getKeyName())
                    ->where($contractor->getQualifiedTeamIdColumn(), $team->getKey()),
            ],
        ];
    }
}
