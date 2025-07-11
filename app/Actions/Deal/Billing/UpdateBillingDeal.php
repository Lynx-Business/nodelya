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

            $dealData = $data->except('deal', 'schedule_data', 'expense_charges')->toArray();

            if ($deal?->exists) {
                $deal->update($dealData);

                $existingChargeIds = collect($data->expense_charges)->pluck('id')->filter();

                $deal->expenseCharges()->when($existingChargeIds->isNotEmpty(), function ($query) use ($existingChargeIds) {
                    $query->whereNotIn('id', $existingChargeIds);
                }, function ($query) {
                    $query->whereNotNull('id');
                })->delete();

                foreach ($data->expense_charges as $charge) {
                    $deal->expenseCharges()->updateOrCreate(
                        ['id' => $charge->id],
                        [
                            'expense_item_id' => $charge->expense_item_id,
                            'amount_in_cents' => $charge->amount_in_cents,
                            'charged_at'      => $charge->charged_at,
                            'model_type'      => 'contractor',
                            'model_id'        => $charge->contractor_id,
                        ],
                    );
                }
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
