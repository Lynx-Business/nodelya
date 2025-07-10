<?php

namespace App\Actions\Client;

use App\Data\Client\Form\ClientFormRequest;
use App\Models\Client;
use Dom\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateClient
{
    use QueueableAction;

    public function execute(ClientFormRequest $data): ?Client
    {

        DB::beginTransaction();

        try {
            $client = $data->client;

            if (! $client?->exists) {
                $client = Client::create($data->except('client')->toArray());
            } else {
                $client->update($data->except('client')->toArray());
            }

            $currentCommentIds = [];
            if ($data->comments) {
                foreach ($data->comments as $commentData) {
                    $comment = $commentData->id
                        ? $client->comments()->find($commentData->id)
                        : new Comment;

                    $comment->message = $commentData->message;
                    $comment->creator()->associate(Auth::user());
                    $client->comments()->save($comment);

                    $currentCommentIds[] = $comment->id;
                }
            }

            $client->comments()
                ->whereNotIn('id', $currentCommentIds)
                ->delete();

            DB::commit();

            return $client;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
