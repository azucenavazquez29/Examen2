<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que hay una sesión de cliente activa
        if (!Session::has('customer_id')) {
            return redirect()->route('home')
                ->with('error', 'Debes iniciar sesión como cliente para acceder a esta sección.')
                ->with('show_modal', true); // Para abrir el modal automáticamente
        }

        // Si la ruta tiene customer_id en la URL, validar que coincida
        $urlCustomerId = $request->query('customer_id');
        $sessionCustomerId = Session::get('customer_id');
        
        if ($urlCustomerId && $urlCustomerId != $sessionCustomerId) {
            // Intento de acceder a cuenta de otro cliente
            return redirect()->route('cliente', ['customer_id' => $sessionCustomerId])
                ->withErrors(['error' => 'No puedes acceder a la información de otro cliente.']);
        }

        return $next($request);
    }
}