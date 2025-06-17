<?php

namespace App\Actions\ProjectDepartment;

use App\Data\ProjectDepartment\Form\ProjectDepartmentFormRequest;
use App\Models\ProjectDepartment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueueableAction\QueueableAction;

class CreateOrUpdateProjectDepartment
{
    use QueueableAction;

    public function __construct(
        //
    ) {}

    public function execute(ProjectDepartmentFormRequest $data): ?ProjectDepartment
    {
        DB::beginTransaction();

        try {
            $projectDepartment = $data->project_department;
            if (! $projectDepartment) {
                $projectDepartment = $data->team->projectDepartments()->create($data->toArray());
            } else {
                $projectDepartment->update($data->toArray());
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage(), $th->getTrace());
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $projectDepartment;
    }
}
