<?php

namespace App\Data\Client\Index;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
    ) {}
}
