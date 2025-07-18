<?php

namespace App\Data\Flow;

use App\Models\FlowCategory;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class FlowCategoryResource extends Resource
{
    public function __construct(
        public int $id,
        public string $name,
        public Lazy|bool $can_view,
        public Lazy|bool $can_update,
        public Lazy|bool $can_trash,
        public Lazy|bool $can_restore,
        public Lazy|bool $can_delete,
    ) {}

    public static function fromModel(FlowCategory $flowCategory): static
    {
        return new static(
            id: $flowCategory->id,
            name: $flowCategory->name,
            can_view: Lazy::create(fn () => $flowCategory->can_view ?? false),
            can_update: Lazy::create(fn () => $flowCategory->can_update ?? false),
            can_trash: Lazy::create(fn () => $flowCategory->can_trash ?? false),
            can_restore: Lazy::create(fn () => $flowCategory->can_restore ?? false),
            can_delete: Lazy::create(fn () => $flowCategory->can_delete ?? false),
        );
    }
}
