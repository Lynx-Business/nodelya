<?php

namespace App\Http\Controllers\Comment;

use App\Data\Comment\CommentRequestData;
use App\Data\Comment\CommentResource;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(CommentRequestData $data): JsonResponse
    {
        $comment = Services::comment()->createOrUpdate->execute($data);

        if (! $comment) {
            Services::toast()->error->execute();

            return response()->json(['message' => __('messages.comments.store.error')], 422);
        }

        return response()->json(
            CommentResource::from($comment->load('creator'))->include('can_view', 'can_update', 'can_delete'),
        );

    }

    /**
     * Update the specified comment in storage.
     */
    public function update(CommentRequestData $data, Comment $comment): JsonResponse
    {
        try {
            $updated = Services::comment()->createOrUpdate->execute($data);

            if (! $updated) {
                Services::toast()->error->execute();

                return response()->json(['message' => __('messages.comments.update.error')], 422);
            }

            return response()->json(
                CommentResource::from($comment->load('creator'))->include('can_view', 'can_update', 'can_delete'),
            );
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), $e->getTrace());

            return response()->json(['message' => __('messages.comments.update.error')], 500);
        }
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        try {
            DB::beginTransaction();
            $comment->delete();
            DB::commit();

            return response()->json(['message' => __('messages.comments.delete.success')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage(), $e->getTrace());
            Services::toast()->error->execute();

            return response()->json(['message' => __('messages.comments.delete.error')], 500);
        }
    }
}
