<?php

namespace App\Actions\Contractor;

use App\Data\Contractor\Form\ContractorFormRequest;
use App\Facades\Services;
use App\Models\Contractor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateContractor
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ContractorFormRequest $data): ?Contractor
    {
        DB::beginTransaction();

        $contractor = $data->contractor;

        try {
            if (! $contractor) {
                $data->additional([
                    'starts_at' => now(),
                ]);
                $contractor = Services::team()->current()->contractors()->create($data->toArray());
            } else {
                $contractor->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $contractor;
    }
}
