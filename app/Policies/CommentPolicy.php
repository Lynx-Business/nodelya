<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function before(User $user): ?bool
    {
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Comment $comment): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Comment $comment): bool
    {
        return false;
    }

    public function trash(User $user, Comment $comment): bool
    {
        return false;
    }

    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return false;
    }
}
