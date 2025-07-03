<?php

namespace App\Actions\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class DeleteEmployeeEndsAt
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(Employee $employee): bool
    {
        DB::beginTransaction();

        try {
            $employee->update([
                'ends_at' => null,
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return false;
        }

        DB::commit();

        return true;
    }
}
