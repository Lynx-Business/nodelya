<?php

namespace App\Http\Middleware\Auth;

use App\Facades\Services;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthNotReadyMiddleware
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
            return $next($request);
        }

        if (! $user->team) {
            // Select the first available team
            Services::user()->selectTeam->execute($user, $team);
        }

        if ($team->accountingPeriods()->doesntExist()) {
            return $next($request);
        }

        return to_route('index');
    }
}
