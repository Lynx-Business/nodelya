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
        public ?int $creator_id = null,
        public ?string $model_type = null,
        public ?int $model_id = null,
        public Lazy|UserResource|null $creator = null,
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
            creator: Lazy::whenLoaded('creator', $comment, fn () => UserResource::from($comment->creator)),
        );
    }
}
