<?php

namespace App\Services;

use App\Actions\Comment\CreateOrUpdateComment;

class CommentService
{
    public function __construct(
        public CreateOrUpdateComment $createOrUpdate,
    ) {}
}
