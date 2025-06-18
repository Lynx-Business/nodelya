<?php

namespace App\Data\Address;

use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
class AddressData extends Data
{
    public function __construct(
        public string $address,
        public ?string $address_complement,
        public string $city,
        public string $postal_code,
        public string $country,
    ) {}

    public static function attributes(): array
    {
        return [
            'address'            => __('models.address.fields.address'),
            'address_complement' => __('models.address.fields.address_complement'),
            'city'               => __('models.address.fields.city'),
            'postal_code'        => __('models.address.fields.postal_code'),
            'country'            => __('models.address.fields.country'),
        ];
    }

    public static function rules(ValidationContext $context): array
    {
        return [
            'address'            => ['required', 'string', 'max:255'],
            'address_complement' => ['nullable', 'string', 'max:255'],
            'city'               => ['required', 'string', 'max:255'],
            'postal_code'        => ['required', 'string', 'max:20'],
            'country'            => ['required', 'string', 'max:100'],
        ];
    }
}
