<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Mostrar el formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar el login del empleado
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        // Buscar al empleado por email
        $staff = DB::table('staff')
            ->where('email', $request->email)
            ->where('active', 1)
            ->first();

        if (!$staff) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('email'));
        }

        // Verificar la contraseña
        $passwordMatch = false;

        // Verificar con SHA-1
        if (sha1($request->password) === $staff->password) {
            $passwordMatch = true;
        }

        if (!$passwordMatch) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('email'));
        }

        try {
            // Actualizar la última fecha de acceso
            DB::table('staff')
                ->where('staff_id', $staff->staff_id)
                ->update(['last_update' => now()]);

            // **REGISTRAR EL ACCESO EN LA TABLA staff_access_log**
            DB::table('staff_access_log')->insert([
                'staff_id' => $staff->staff_id,
                'login_time' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'access_type' => 'login'
            ]);

            Log::info('Acceso registrado para staff_id: ' . $staff->staff_id);

        } catch (\Exception $e) {
            // Log del error pero no interrumpir el login
            Log::error('Error al registrar acceso: ' . $e->getMessage());
        }

        // Guardar la información del empleado en la sesión
        Session::put('staff_id', $staff->staff_id);
        Session::put('staff_name', $staff->first_name . ' ' . $staff->last_name);
        Session::put('staff_email', $staff->email);
        Session::put('store_id', $staff->store_id);
        Session::put('user_role', $staff->role ?? 'empleado');

        return redirect()->route('empleado.dashboard')->with('success', '¡Bienvenido ' . $staff->first_name . '!');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $staffId = Session::get('staff_id');

        // **REGISTRAR EL LOGOUT**
        if ($staffId) {
            try {
                DB::table('staff_access_log')->insert([
                    'staff_id' => $staffId,
                    'login_time' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'access_type' => 'logout'
                ]);

                Log::info('Logout registrado para staff_id: ' . $staffId);

            } catch (\Exception $e) {
                Log::error('Error al registrar logout: ' . $e->getMessage());
            }
        }

        Session::flush();
        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }

    /**
     * Verificar si hay una sesión activa
     */
    public function check()
    {
        if (Session::has('staff_id')) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'name' => Session::get('staff_name'),
                    'email' => Session::get('staff_email'),
                    'role' => Session::get('user_role'),
                    'store_id' => Session::get('store_id')
                ]
            ]);
        }

        if (Session::has('customer_id')) {
            return response()->json([
                'authenticated' => true,
                'user' => [
                    'name' => Session::get('customer_name'),
                    'email' => Session::get('customer_email'),
                    'role' => 'cliente'
                ]
            ]);
        }

        return response()->json(['authenticated' => false]);
    }

    /**
     * Procesar el login del cliente
     */
    public function loginCliente(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ser un correo electrónico válido',
            'password.required' => 'La contraseña es obligatoria'
        ]);

        // Buscar al cliente por email
        $customer = DB::table('customer')
            ->where('email', $request->email)
            ->where('active', 1)
            ->first();

        if (!$customer) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('email'));
        }

        // Verificar la contraseña (usando address_id como contraseña)
        if ($customer->address_id != $request->password) {
            return back()->withErrors([
                'email' => 'Las credenciales no coinciden con nuestros registros.'
            ])->withInput($request->only('email'));
        }

        // Guardar la información del cliente en la sesión
        Session::put('customer_id', $customer->customer_id);
        Session::put('customer_name', $customer->first_name . ' ' . $customer->last_name);
        Session::put('customer_email', $customer->email);
        Session::put('customer_address_id', $customer->address_id);
        Session::put('customer_store_id', $customer->store_id);
        Session::put('user_role', 'cliente');

        return redirect()->route('cliente', ['customer_id' => $customer->customer_id])
            ->with('success', '¡Bienvenido ' . $customer->first_name . '!');
    }

    /**
     * Mostrar formulario de registro de cliente
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Registrar un nuevo cliente
     */
    public function registerCliente(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email|max:50|unique:customer,email',
            'address' => 'required|string|max:50',
            'district' => 'required|string|max:20',
            'city_id' => 'required|integer|exists:city,city_id',
            'phone' => 'required|string|max:20',
            'postal_code' => 'nullable|string|max:10',
            'store_id' => 'required|integer|exists:store,store_id'
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'address.required' => 'La dirección es obligatoria.',
            'district.required' => 'El distrito es obligatorio.',
            'city_id.required' => 'La ciudad es obligatoria.',
            'phone.required' => 'El teléfono es obligatorio.',
            'store_id.required' => 'La sucursal es obligatoria.'
        ]);

        try {
            // Primero crear la dirección
            $addressId = DB::table('address')->insertGetId([
                'address' => $request->address,
                'address2' => $request->address2 ?? null,
                'district' => $request->district,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'last_update' => now()
            ]);

            // Luego crear el cliente
            $customerId = DB::table('customer')->insertGetId([
                'store_id' => $request->store_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'address_id' => $addressId,
                'active' => 1,
                'create_date' => now(),
                'last_update' => now()
            ]);

            // Iniciar sesión automáticamente
            Session::put('customer_id', $customerId);
            Session::put('customer_name', $request->first_name . ' ' . $request->last_name);
            Session::put('customer_email', $request->email);
            Session::put('customer_address_id', $addressId);
            Session::put('customer_store_id', $request->store_id);
            Session::put('user_role', 'cliente');

            return redirect()->route('cliente.dashboard')->with('success', '¡Registro exitoso! Bienvenido ' . $request->first_name . '!');

        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'Hubo un error al registrar tu cuenta. Por favor intenta de nuevo.'
            ])->withInput();
        }
    }

    /**
     * Cerrar sesión de cliente
     */
    public function logoutCliente()
    {
        Session::flush();
        return redirect()->route('home')->with('success', 'Sesión cerrada correctamente');
    }


    // Mostrar formulario de recuperación
public function showForgotForm()
{
    return view('auth.forgot-password');
}

// Enviar correo con la contraseña
public function sendResetLink(Request $request)
{
    $request->validate([
        'email' => 'required|email'
    ]);

    // Buscar cliente por email
    $customer = DB::table('customer')
        ->join('address', 'customer.address_id', '=', 'address.address_id')
        ->where('customer.email', $request->email)
        ->select('customer.*', 'address.address_id')
        ->first();

    if (!$customer) {
        return back()->withErrors([
            'email' => 'No encontramos un cliente con ese correo electrónico.'
        ]);
    }

    // Generar token
    $token = Str::random(60);
    
    // Guardar token en la base de datos
    DB::table('password_resets')->updateOrInsert(
        ['email' => $request->email],
        [
            'email' => $request->email,
            'token' => hash('sha256', $token),
            'created_at' => now()
        ]
    );

    // Enviar correo
    Mail::send('emails.password-reset', [
        'customerName' => $customer->first_name,
        'password' => $customer->address_id,
        'email' => $customer->email
    ], function($message) use ($customer) {
        $message->to($customer->email)
                ->subject('Recuperación de Contraseña - DarkMovies');
    });

    return back()->with('success', 'Te hemos enviado un correo con tu contraseña.');
}
}