<?php

namespace App\Actions\Contractor;

use App\Models\Contractor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class UpdateContractorEndsAt
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(Contractor $contractor): bool
    {
        DB::beginTransaction();

        try {
            $contractor->touch('ends_at');
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return false;
        }

        DB::commit();

        return true;
    }
}
