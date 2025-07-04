<?php

namespace App\Http\Controllers\Contractor;

use App\Facades\Services;
use App\Http\Controllers\Controller;
use App\Models\Contractor;

class ContractorEndsAtController extends Controller
{
    public function update(Contractor $contractor)
    {
        $success = Services::contractor()->updateEndsAt->execute($contractor);
        if (! $success) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.contractors.update.success'));

        return back();
    }

    public function destroy(Contractor $contractor)
    {
        $success = Services::contractor()->deleteEndsAt->execute($contractor);
        if (! $success) {
            Services::toast()->error->execute();

            return back();
        }

        Services::toast()->success->execute(__('messages.contractors.update.success'));

        return back();
    }
}
