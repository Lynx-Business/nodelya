<?php

namespace App\Data\Client;

use App\Data\Address\AddressData;
use App\Models\Client;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class ClientResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public AddressData $address,
        public Lazy|bool $can_view,
        public Lazy|bool $can_update,
        public Lazy|bool $can_trash,
        public Lazy|bool $can_restore,
        public Lazy|bool $can_delete,
    ) {}

    public static function fromModel(Client $client): static
    {
        return new static(
            id: $client->id,
            name: $client->name,
            address: $client->address,
            can_view: Lazy::create(fn () => $client->can_view),
            can_update: Lazy::create(fn () => $client->can_update),
            can_trash: Lazy::create(fn () => $client->can_trash),
            can_restore: Lazy::create(fn () => $client->can_restore),
            can_delete: Lazy::create(fn () => $client->can_delete),
        );
    }
}
