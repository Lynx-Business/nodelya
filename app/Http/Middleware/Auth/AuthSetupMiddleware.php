<?php

namespace App\Http\Middleware\Auth;

use App\Facades\Services;
use App\Models\AccountingPeriod;
use App\Models\Team;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class AuthSetupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        abort_if(! $user, Response::HTTP_FORBIDDEN);

        /** @var \App\Models\User $user */
        $team = $user->team_id && $user->hasTeam($user->team_id)
            ? $user->team
            : $user->teams->first();

        if (! $team) {
            $user->update(['team_id' => null]);

            return $user->can('create', Team::class)
                ? to_route('auth.setup.step-one.edit')
                : to_route('auth.setup.not-ready');
        }

        // Select the first available team
        Services::user()->selectTeam->execute($user, $team);
        if (! $team->currentAccountingPeriod) {
            Services::team()->setCurrent($team);

            return $user->can('create', AccountingPeriod::class)
                ? to_route('auth.setup.step-two.edit')
                : to_route('auth.setup.not-ready');
        }

        if (Route::is('teams.*')) {
            /** @var Team|string|int|null $routeTeam */
            $routeTeam = $request->team;
            if ($routeTeam && $user->hasTeam($routeTeam)) {
                // If the user is accessing their configuration set to current route team
                $team = $routeTeam;
            }
        }
        Services::team()->setCurrent($team);

        return $next($request);
    }
}
