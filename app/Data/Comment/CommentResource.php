<?php

namespace App\Data\Comment;

use App\Data\User\UserResource;
use App\Models\Comment;
use Spatie\LaravelData\Lazy;
use Spatie\LaravelData\Resource;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class CommentResource extends Resource
{
    public function __construct(
        public int $id,
        public string $message,
        public string $created_at,
        public string $updated_at,
        public bool $is_edited,
        public ?int $creator_id,
        public ?string $model_type,
        public ?int $model_id,
        public Lazy|UserResource|null $creator = null,
        public Lazy|bool $can_view,
        public Lazy|bool $can_update,
        public Lazy|bool $can_trash,
        public Lazy|bool $can_restore,
        public Lazy|bool $can_delete,
    ) {}

    public static function fromModel(Comment $comment): static
    {
        return new static(
            id: $comment->id,
            message: $comment->message,
            created_at: $comment->created_at->toIso8601String(),
            updated_at: $comment->updated_at->toIso8601String(),
            is_edited: $comment->created_at->ne($comment->updated_at),
            creator_id: $comment->creator_id,
            model_type: $comment->model_type,
            model_id: $comment->model_id,
            can_view: Lazy::create(fn () => $comment->can_view),
            can_update: Lazy::create(fn () => $comment->can_update),
            can_trash: Lazy::create(fn () => $comment->can_trash),
            can_restore: Lazy::create(fn () => $comment->can_restore),
            can_delete: Lazy::create(fn () => $comment->can_delete),
            creator: Lazy::whenLoaded('creator', $comment, fn () => UserResource::from($comment->creator)),
        );
    }
}
