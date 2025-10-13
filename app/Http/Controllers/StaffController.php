<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Store;
use App\Models\Address;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    /**
     * Listar todos los empleados
     */
    public function index(Request $request)
    {
        $query = Staff::with('store', 'address.city.country');

        // Filtrar por tienda
        if ($request->has('store_id') && $request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        // Filtrar por estado activo
        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        $staff = $query->paginate(15);
        $stores = Store::all();
        return view('admin.staff.index', compact('staff', 'stores'));
    }

    /**
     * Mostrar detalle de un empleado
     */
    public function show(Staff $staff)
    {
        $staff->load(['store', 'address', 'rentals' => function($q) {
            $q->orderBy('rental_date', 'desc')->limit(10);
        }]);

        $stats = [
            'total_rentals' => $staff->rentals()->count(),
            'active_rentals' => $staff->rentals()->whereNull('return_date')->count(),
        ];

        return view('admin.staff.show', compact('staff', 'stats'));
    }

    /**
     * Formulario para crear un empleado
     */
    public function create()
    {

        $stores = Store::all();
        $cities = City::all();
        return view('admin.staff.create', compact('stores','cities'));
    }

    /**
     * Guardar un nuevo empleado
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email|max:50|unique:staff,email',
            'username' => 'required|string|max:16|unique:staff,username',
            'store_id' => 'required|exists:store,store_id',
            'address' => 'required|string|max:50',
            'address2' => 'nullable|string|max:50',
            'district' => 'required|string|max:20',
            'city_id' => 'required|exists:city,city_id',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:20',
        ]);

        // Crear dirección
        $address = Address::create([
            'address' => $validated['address'],
            'address2' => $validated['address2'] ?? null,
            'district' => $validated['district'],
            'city_id' => $validated['city_id'],
            'postal_code' => $validated['postal_code'] ?? null,
            'phone' => $validated['phone'],
        ]);

        // Generar contraseña temporal
        $temporalPassword = Str::random(12);

        // Crear empleado
        $staff = Staff::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($temporalPassword),
            'store_id' => $validated['store_id'],
            'address_id' => $address->address_id,
            'active' => true,
        ]);

        // TODO: Enviar email con credenciales temporales
        // Mail::send('emails.staff-credentials', [...], function($message) { ... });

        return redirect()->route('staff.show', $staff->staff_id)
            ->with('success', 'Empleado creado exitosamente')
            ->with('temp_password', $temporalPassword);
    }

    /**
     * Formulario para editar un empleado
     */
    public function edit(Staff $staff)
    {
        $staff->load('store', 'address');
        $stores = Store::all();

        return view('admin.staff.edit', compact('staff', 'stores'));
    }

    /**
     * Actualizar información de un empleado
     */
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => "required|email|max:50|unique:staff,email,{$staff->staff_id},staff_id",
            'username' => "required|string|max:16|unique:staff,username,{$staff->staff_id},staff_id",
            'store_id' => 'required|exists:store,store_id',
            'address' => 'required|string|max:50',
            'address2' => 'nullable|string|max:50',
            'district' => 'required|string|max:20',
            'city_id' => 'required|exists:city,city_id',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:20',
        ]);

        // Actualizar dirección
        $staff->address->update([
            'address' => $validated['address'],
            'address2' => $validated['address2'] ?? null,
            'district' => $validated['district'],
            'city_id' => $validated['city_id'],
            'postal_code' => $validated['postal_code'] ?? null,
            'phone' => $validated['phone'],
        ]);

        // Actualizar empleado
        $staff->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'store_id' => $validated['store_id'],
        ]);

        return redirect()->route('staff.show', $staff->staff_id)
            ->with('success', 'Empleado actualizado exitosamente');
    }

    /**
     * Cambiar estado activo/inactivo de un empleado
     */
    public function toggleActive(Staff $staff)
    {
        $staff->update(['active' => !$staff->active]);

        $status = $staff->active ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "Empleado {$status} exitosamente");
    }

    /**
     * Resetear contraseña de un empleado
     */
    public function resetPassword(Staff $staff)
    {
        $newPassword = Str::random(12);
        $staff->update(['password' => Hash::make($newPassword)]);

        // TODO: Enviar email con nueva contraseña temporal
        // Mail::send('emails.password-reset', [...], function($message) { ... });

        return redirect()->back()
            ->with('success', 'Contraseña reseteada exitosamente')
            ->with('new_password', $newPassword);
    }

    /**
     * Bloquear cuenta de un empleado
     */
    public function lockAccount(Staff $staff)
    {
        $staff->update(['active' => false]);

        return redirect()->back()
            ->with('success', 'Cuenta de empleado bloqueada exitosamente');
    }

    /**
     * Desbloquear cuenta de un empleado
     */
    public function unlockAccount(Staff $staff)
    {
        $staff->update(['active' => true]);

        return redirect()->back()
            ->with('success', 'Cuenta de empleado desbloqueada exitosamente');
    }

    /**
     * Cambiar empleado a otra tienda
     */
    public function changeStore(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:store,store_id',
        ]);

        // Si es gerente, no permitir cambio de tienda
        $store = Store::where('manager_staff_id', $staff->staff_id)->first();
        if ($store) {
            return redirect()->back()
                ->with('error', 'No se puede cambiar de tienda a un gerente');
        }

        $staff->update(['store_id' => $validated['store_id']]);

        return redirect()->back()
            ->with('success', 'Empleado asignado a nueva tienda exitosamente');
    }

    /**
     * Listar empleados de una tienda específica
     */
    public function byStore(Store $store)
    {
        $staff = $store->staff()->with('address')->get();

        return view('admin.staff.by-store', compact('store', 'staff'));
    }

    /**
     * Eliminar un empleado
     */
    public function destroy(Staff $staff)
    {
        // Verificar que no sea gerente
        $store = Store::where('manager_staff_id', $staff->staff_id)->first();
        if ($store) {
            return redirect()->route('staff.index')
                ->with('error', 'No se puede eliminar a un gerente. Asigne otro gerente primero.');
        }

        // Eliminar dirección asociada
        $address = $staff->address;
        $staff->delete();
        $address->delete();

        return redirect()->route('staff.index')
            ->with('success', 'Empleado eliminado exitosamente');
    }
}