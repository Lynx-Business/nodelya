<?php

namespace App\Actions\Deal\Commercial;

use App\Data\Deal\Commercial\Form\CommercialDealFormRequest;
use App\Models\Deal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateCommercialDeal
{
    use QueueableAction;

    public function execute(CommercialDealFormRequest $data): ?Deal
    {
        DB::beginTransaction();

        try {
            $deal = $data->deal;

            $dealData = $data->except('deal', 'schedule_data')->toArray();

            if (! $deal?->exists) {
                $deal = Deal::create($dealData);
            } else {
                $deal->update($dealData);
            }

            DB::commit();

            return $deal;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
