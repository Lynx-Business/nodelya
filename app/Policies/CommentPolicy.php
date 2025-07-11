<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class CommentPolicy
{
    public function before(User $user): ?bool
    {
        if (! $user->hasPermissionTo(PermissionName::COMMENT->value)) {
            return false;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Comment $comment): bool
    {

        return $comment->creator && $comment->creator->team_id === $user->team_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {

        return $comment->creator && $comment->creator->id === $user->id;
    }

    public function trash(User $user, Comment $comment): bool
    {
        if (property_exists($comment, 'is_trashed') && $comment->is_trashed) {
            return false;
        }
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! ($comment->creator && $comment->creator->team_id === $user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function restore(User $user, Comment $comment): bool
    {
        if (! property_exists($comment, 'is_trashed') || ! $comment->is_trashed) {
            return false;
        }
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! ($comment->creator && $comment->creator->team_id === $user->team_id)) {
            return false;
        }

        return $user->is_owner;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! ($comment->creator && $comment->creator->id === $user->id)) {
            return false;
        }

        return $user->is_owner;
    }
}
