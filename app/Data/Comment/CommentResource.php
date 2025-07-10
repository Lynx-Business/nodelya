<?php

namespace App\Data\Comment;

use App\Models\Comment;
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
    ) {}

    public static function fromModel(Comment $comment): self
    {
        return new self(
            id: $comment->id,
            message: $comment->message,
            created_at: $comment->created_at->toIso8601String(),
            updated_at: $comment->updated_at->toIso8601String(),
            is_edited: $comment->created_at->ne($comment->updated_at),
        );
    }
}
