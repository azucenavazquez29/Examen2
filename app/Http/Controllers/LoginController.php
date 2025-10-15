<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->username;
        $password = sha1($request->password);

        $staff = DB::table('staff')
            ->where('username', $username)
            ->where('password', $password)
            ->where('active', 1)
            ->first();

        if ($staff) {
            Session::put('staff_id', $staff->staff_id);
            Session::put('store_id', $staff->store_id);
            Session::put('staff_name', $staff->first_name . ' ' . $staff->last_name);

            // Actualizar last_update en staff
            DB::table('staff')
                ->where('staff_id', $staff->staff_id)
                ->update(['last_update' => now()]);

            // Registrar el acceso en el historial
            DB::table('staff_access_log')->insert([
                'staff_id' => $staff->staff_id,
                'login_time' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'access_type' => 'login'
            ]);

            return redirect()->route('empleado.dashboard')
                ->with('success', '¡Bienvenido ' . $staff->first_name . '!');
        }

        return back()->withErrors([
            'username' => 'Las credenciales no son correctas.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $staffId = Session::get('staff_id');

        if ($staffId) {
            // Registrar el logout
            DB::table('staff_access_log')->insert([
                'staff_id' => $staffId,
                'login_time' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'access_type' => 'logout'
            ]);
        }

        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}