<?php

namespace App\Actions\Employee;

use App\Data\Employee\Form\UpdateEmployeeEndsAtRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class UpdateEmployeeEndsAt
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(UpdateEmployeeEndsAtRequest $data): bool
    {
        DB::beginTransaction();

        $employee = $data->employee;

        try {
            $employee->update($data->toArray());
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return false;
        }

        DB::commit();

        return true;
    }
}
