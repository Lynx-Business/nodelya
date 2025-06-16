<?php

namespace App\Data\Client\Index;

use Spatie\LaravelData\Attributes\AutoInertiaLazy;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\PaginatedDataCollection;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientIndexProps extends Resource
{
    public function __construct(
        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientIndexResource::class)]
        public Lazy|PaginatedDataCollection $clients,
    ) {}
}
