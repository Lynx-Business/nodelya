<?php

namespace App\Http\Controllers\Employee;

use App\Data\Employee\Form\UpdateEmployeeEndsAtRequest;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Employee;

class EmployeeEndsAtController extends Controller
{
    public function update(Employee $employee, UpdateEmployeeEndsAtRequest $data)
    {
        $success = Services::employee()->updateEndsAt->execute($data);
        if (! $success) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.employees.update.success'));

        return back();
    }

    public function destroy(Employee $employee)
    {
        $success = Services::employee()->deleteEndsAt->execute($employee);
        if (! $success) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.employees.update.success'));

        return back();
    }
}
