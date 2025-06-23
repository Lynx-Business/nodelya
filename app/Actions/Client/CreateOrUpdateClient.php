<?php

namespace App\Actions\Client;

use App\Data\Client\Form\ClientFormRequest;
use App\Models\Client;
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

            DB::commit();

            return $client;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
