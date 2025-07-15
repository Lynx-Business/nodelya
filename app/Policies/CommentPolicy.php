<?php

namespace App\Policies;

use App\Enums\Permission\PermissionName;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        return Gate::check('view', $comment->model);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {

        return $comment->creator_id === $user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        if ($user->is_admin && Route::is('admin.*')) {
            return true;
        }
        if (! ($comment->creator_id === $user->id)) {
            return false;
        }

        return true;
    }
}
