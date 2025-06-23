<?php

namespace App\Data\Deal\Commercial\Index;

use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
    ) {}
}
