<?php

namespace App\Http\Controllers\Auth\Setup;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AuthSetupNotReadyController extends Controller
{
    public function index()
    {
        return Inertia::render('auth/setup/NotReady');
    }
}
