<?php

namespace App\Actions\Client;

use App\Data\Client\Form\ClientFormRequest;
use App\Facades\Services;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateClient
{
    use QueueableAction;

    public function execute(ClientFormRequest $data): ?Client
    {

        DB::beginTransaction();
        $currentId = Services::team()->currentId();

        try {
            $client = $data->client;

            if (! $client?->exists) {
                $clientData = $data->except('client')->toArray();
                $clientData['team_id'] = $currentId;

                $client = Client::create($clientData);
            } else {
                $client->update($data->except('client')->toArray());
            }

            DB::commit();

            return $client;
        } catch (\Throwable $th) {
            DB::rollBack();

            return null;
        }
    }
}
