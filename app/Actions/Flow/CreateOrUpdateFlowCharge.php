<?php

namespace App\Actions\Flow;

use App\Data\Flow\Form\FlowFormRequest;
use App\Models\FlowCharge;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateFlowCharge
{
    use QueueableAction;

    public function execute(FlowFormRequest $data): ?bool
    {
        DB::beginTransaction();
        try {
            foreach ($data->charges as $charge) {
                $categoryId = $charge->category_id;
                if ($charge->id) {
                    FlowCharge::where('id', $charge->id)
                        ->update([
                            'flow_category_id' => $categoryId,
                            'amount_in_cents'  => $charge->amount_in_cents,
                            'charged_at'       => $charge->date,
                        ]);
                } else {
                    FlowCharge::create([
                        'flow_category_id' => $categoryId,
                        'amount_in_cents'  => $charge->amount_in_cents,
                        'charged_at'       => $charge->date,
                    ]);
                }
            }
            DB::commit();

            return true;
        } catch (\Throwable $e) {
            Log::error($e->getMessage(), $e->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
