<?php

namespace App\Data\Flow;

use App\Models\FlowCharge;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FlowChargeResource extends Resource
{
    public function __construct(
        public int $id,
        public int $team_id,
        public int $flow_category_id,
        public int $amount_in_cents,
        public float $amount,
        public string $charged_at,
        public ?string $created_at,
        public ?string $updated_at,
        public Lazy|bool $can_view,
        public Lazy|bool $can_update,
        public Lazy|bool $can_trash,
        public Lazy|bool $can_restore,
        public Lazy|bool $can_delete,
    ) {}

    public static function fromModel(FlowCharge $flowCharge): static
    {
        return new static(
            id: $flowCharge->id,
            team_id: $flowCharge->team_id,
            flow_category_id: $flowCharge->flow_category_id,
            amount_in_cents: $flowCharge->amount_in_cents,
            amount: $flowCharge->amount,
            charged_at: $flowCharge->charged_at?->toDateString(),
            created_at: $flowCharge->created_at?->toDateTimeString(),
            updated_at: $flowCharge->updated_at?->toDateTimeString(),
            can_view: Lazy::create(fn () => $flowCharge->can_view ?? false),
            can_update: Lazy::create(fn () => $flowCharge->can_update ?? false),
            can_trash: Lazy::create(fn () => $flowCharge->can_trash ?? false),
            can_restore: Lazy::create(fn () => $flowCharge->can_restore ?? false),
            can_delete: Lazy::create(fn () => $flowCharge->can_delete ?? false),
        );
    }
}
