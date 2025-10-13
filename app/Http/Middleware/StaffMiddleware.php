<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('staff_id')) {
            return redirect()->route('home')->withErrors([
                'error' => 'Debes iniciar sesión como empleado para acceder a esta sección.'
            ]);
        }

        return $next($request);
    }
}