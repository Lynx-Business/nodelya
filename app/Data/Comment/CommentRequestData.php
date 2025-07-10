<?php

namespace App\Data\Comment;

use Spatie\LaravelData\Data;

class CommentRequestData extends Data
{
    public function __construct(
        public ?int $id,
        public string $message,
    ) {}
}
