<?php

namespace App\Http\Controllers\Auth\Setup;

use App\Data\Team\Form\TeamFormRequest;
use App\Facades\Services;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthSetupStepOneController extends Controller
{
    public function edit()
    {
        return Inertia::render('auth/setup/StepOne');
    }

    public function update(TeamFormRequest $data)
    {
        $team = Services::team()->createOrUpdate->execute($data);

        if ($team == null) {
            Services::toast()->error->execute();

            return back();
        }

        Services::user()->selectTeam->execute(Auth::user(), $team);

        return to_route('auth.setup.step-two.edit');
    }
}
