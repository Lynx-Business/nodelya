<?php

namespace App\Actions\Comment;

use App\Data\Comment\CommentRequestData;
use App\Models\Comment;
use DB;
use Illuminate\Support\Facades\Log;

class CreateOrUpdateComment
{
    public function execute(CommentRequestData $data): ?Comment
    {

        DB::beginTransaction();

        try {
            $comment = $data->comment;

            if (! $comment?->exists) {
                $comment = Comment::create($data->except('comment')->toArray());
            } else {
                $comment->update($data->except('comment')->toArray());
            }

            DB::commit();

            return $comment;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
