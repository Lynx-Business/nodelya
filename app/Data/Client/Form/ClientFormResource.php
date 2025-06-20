<?php

namespace App\Data\Client\Form;

use App\Data\Address\AddressData;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientFormResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public AddressData $address,
    ) {}
}
