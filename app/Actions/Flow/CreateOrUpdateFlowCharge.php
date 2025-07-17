<?php

namespace App\Actions\Flow;

use App\Data\Flow\Form\FlowFormRequest;
use App\Models\FlowCharge;
use App\Services\FlowService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateOrUpdateFlowCharge
{
    public function execute(FlowFormRequest $data): ?FlowCharge
    {
        DB::beginTransaction();
        try {
            $flowService = app(FlowService::class);
            foreach ($data->charges as $charge) {
                $categoryId = $charge->category_id;
                if (! $categoryId && $charge->category_name) {
                    $category = $flowService->createOrUpdateCategory->execute([
                        'name' => $charge->category_name,
                    ]);
                    $categoryId = $category?->id;
                }
                FlowCharge::create([
                    'flow_category_id' => $categoryId,
                    'amount'           => $charge->amount,
                    'amount_in_cents'  => (int) ($charge->amount * 100),
                    'charged_at'       => $charge->date,
                ]);
            }
            DB::commit();

            return $charge;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
