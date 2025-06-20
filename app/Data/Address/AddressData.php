<?php

namespace App\Data\Address;

use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class AddressData extends Data
{
    public function __construct(
        #[Max(255)]
        public string $address,
        #[Max(255)]
        public ?string $address_complement,
        #[Max(255)]
        public string $city,
        #[Max(20)]
        public string $postal_code,
        #[Max(100)]
        public string $country,
    ) {}

    public static function attributes(): array
    {
        return [
            'address'            => __('data.address.fields.address'),
            'address_complement' => __('data.address.fields.address_complement'),
            'city'               => __('data.address.fields.city'),
            'postal_code'        => __('data.address.fields.postal_code'),
            'country'            => __('data.address.fields.country'),
        ];
    }
}
