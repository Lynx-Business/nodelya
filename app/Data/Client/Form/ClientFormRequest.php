<?php

namespace App\Data\Client\Form;

use App\Data\Address\AddressData;
use App\Models\Client;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ClientFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('client')]
        public ?Client $client,

        public string $name,

        public AddressData $address,
    ) {}

    public static function attributes(): array
    {
        return [
            'name'                => __('models.client.fields.name'),
            'address.line1'       => __('models.address.fields.line1'),
            'address.line2'       => __('models.address.fields.line2'),
            'address.city'        => __('models.address.fields.city'),
            'address.state'       => __('models.address.fields.state'),
            'address.postal_code' => __('models.address.fields.postal_code'),
            'address.country'     => __('models.address.fields.country'),

        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            //
        ];
    }
}
