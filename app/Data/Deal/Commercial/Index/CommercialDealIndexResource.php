<?php

namespace App\Data\Deal\Commercial\Index;

use App\Models\Client;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommercialDealIndexResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public float $amount,
        public string $code,
        public int $success_rate,
        public ?int $duration_in_months,
        public string $ordered_at,
        public ?string $starts_at,
        public ?Client $client,
        public bool $can_view,
        public bool $can_update,
        public bool $can_trash,
        public bool $can_restore,
        public bool $can_delete,
        public array $monthly_expenses,
    ) {}
}
