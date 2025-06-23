<?php

namespace App\Http\Controllers\Auth\Setup;

use App\Data\AccountingPeriod\Form\AccountingPeriodFormRequest;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AuthSetupStepTwoController extends Controller
{
    public function edit()
    {
        return Inertia::render('auth/setup/StepTwo');
    }

    public function update(AccountingPeriodFormRequest $data)
    {
        $accountingPeriod = Services::accountingPeriod()->createOrUpdate->execute($data);

        if ($accountingPeriod == null) {
            Services::toast()->error->execute();

            return back();
        }

        return to_route('index');
    }
}
