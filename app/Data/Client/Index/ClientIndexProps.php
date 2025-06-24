<?php

namespace App\Data\Client\Index;

use App\Attributes\EnumArrayOf;
use App\Enums\Trashed\TrashedFilter;
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
        public ClientIndexRequest $request,

        #[AutoInertiaLazy]
        #[DataCollectionOf(ClientIndexResource::class)]
        public Lazy|PaginatedDataCollection $clients,

        #[AutoInertiaLazy]
        #[EnumArrayOf(TrashedFilter::class)]
        public Lazy|array $trashedFilters,
    ) {}
}
