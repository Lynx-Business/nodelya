<?php

namespace App\Data\Client\Form;

use App\Data\Address\AddressData;
use App\Models\Client;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Hidden;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class ClientFormRequest extends Data
{
    public function __construct(
        #[Hidden]
        #[FromRouteParameter('client')]
        public ?Client $client,

        #[Max(255)]
        public string $name,

        public AddressData $address,
    ) {}

    public static function attributes(): array
    {
        return [
            'name'                       => __('models.client.fields.name'),
            'address.address'            => __('models.address.fields.address'),
            'address.address_complement' => __('models.address.fields.address_complement'),
            'address.city'               => __('models.address.fields.city'),
            'address.postal_code'        => __('models.address.fields.postal_code'),
            'address.country'            => __('models.address.fields.country'),
        ];
    }
}
