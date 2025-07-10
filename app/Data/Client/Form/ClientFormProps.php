<?php

namespace App\Data\Client\Form;

use App\Data\Client\ClientResource;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientFormProps extends Resource
{
    public function __construct(
        public ?ClientResource $client,
    ) {}
}
