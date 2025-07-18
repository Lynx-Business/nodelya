<?php

namespace App\Actions\Flow;

use App\Models\FlowCategory;
use DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateFlowCategory
{
    use QueueableAction;

    public function execute(array $data): ?FlowCategory
    {
        DB::beginTransaction();
        try {
            $category = isset($data['id']) ? FlowCategory::find($data['id']) : null;
            if (! $category) {
                $category = FlowCategory::create($data);
            } else {
                $category->update($data);
            }
            DB::commit();

            return $category;
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }
    }
}
