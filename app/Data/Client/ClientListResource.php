<?php

namespace App\Data\Client;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientListResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
