<?php

namespace App\Data\Client;

use App\Models\Client;
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
class ClientOneOrManyRequest extends Data
{
    public function __construct(

        #[FromRouteParameter('client')]
        #[ExcludeWith('ids')]
        public ?int $client = null,

        #[Min(1)]
        #[RequiredWithout('client')]
        /** @var array<int> */
        public ?array $ids = null,
    ) {}

    public static function attributes(): array
    {
        return [
            'client' => __('models.client.name.one'),
            'ids'    => __('models.client.name.many'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        $client = app(Client::class);

        return [
            'ids.*' => ['integer', 'distinct', Rule::exists($client->getTable(), $client->getKeyName())],
        ];
    }
}
