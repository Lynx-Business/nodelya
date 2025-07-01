<?php

namespace App\Actions\Deal\Billing;

use App\Data\Deal\Billing\Form\BillingDealFormRequest;
use App\Models\Deal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class UpdateBillingDeal
{
    use QueueableAction;

    public function execute(BillingDealFormRequest $data): ?Deal
    {
        DB::beginTransaction();

        try {
            $deal = $data->deal;

            $dealData = $data->except('deal', 'schedule_data')->toArray();

            if ($deal?->exists) {
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
