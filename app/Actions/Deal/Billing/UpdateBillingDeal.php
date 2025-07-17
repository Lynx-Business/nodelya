<?php

namespace App\Actions\Deal\Billing;

use App\Data\Deal\Billing\Form\BillingDealFormRequest;
use App\Data\Expense\Charge\Form\ExpenseChargeFormRequest;
use App\Facades\Services;
use App\Models\Contractor;
use App\Models\Deal;
use App\Models\ExpenseCharge;
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
                    $expenseCharge = isset($charge->id) ? ExpenseCharge::find($charge->id) : null;
                    $expenseChargeFormRequest = new ExpenseChargeFormRequest(
                        deal_id: $deal->id,
                        expense_charge: $expenseCharge,
                        model_type: app(Contractor::class)->getMorphClass(),
                        model_id: $charge->contractor_id ?? null,
                        expense_item_id: $charge->expense_item_id,
                        amount: $charge->amount,
                        charged_at: $charge->charged_at,
                    );

                    Services::expense()->createOrUpdateCharge->execute($expenseChargeFormRequest);
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
