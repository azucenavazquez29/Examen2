<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('staff_id')) {
            return redirect()->route('home')
                ->with('error', 'Debes iniciar sesión como empleado.');
        }

        if (Session::get('user_role') !== 'admin') {
            return redirect()->route('empleado')
                ->with('error', 'No tienes permisos de administrador.');
        }

        return $next($request);
    }
}