<?php

namespace App\Actions\Employee;

use App\Data\Employee\Form\EmployeeFormRequest;
use App\Facades\Services;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateEmployee
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(EmployeeFormRequest $data): ?Employee
    {
        DB::beginTransaction();

        $employee = $data->employee;

        try {
            if (! $employee) {
                $employee = Services::team()->current()->employees()->create($data->toArray());
            } else {
                $employee->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $employee;
    }
}
