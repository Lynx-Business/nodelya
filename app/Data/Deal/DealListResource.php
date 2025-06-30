<?php

namespace App\Data\Deal;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class DealListResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public float $amount,
    ) {}
}
