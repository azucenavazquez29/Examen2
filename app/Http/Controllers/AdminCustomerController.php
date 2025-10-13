<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminCustomerController extends Controller
{
    /**
     * Listar todos los clientes con filtros
     */
    public function index(Request $request)
    {
        $query = Customer::with('store', 'address.city.country');

        // Filtrar por tienda
        if ($request->has('store_id') && $request->store_id) {
            $query->where('store_id', $request->store_id);
        }

        // Filtrar por estado activo
        if ($request->has('active')) {
            $query->where('active', $request->active);
        }

        // Buscar por nombre o email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->paginate(15);
        $stores = Store::all();

        return view('admin.customers.index', compact('customers', 'stores'));
    }

    /**
     * Mostrar detalle de un cliente
     */
    public function show(Customer $customer)
    {
        $customer->load(['store', 'address.city.country', 'rentals' => function($q) {
            $q->orderBy('rental_date', 'desc')->with('inventory.film');
        }]);

        $stats = [
            'total_rentals' => $customer->rentals()->count(),
            'active_rentals' => $customer->rentals()->whereNull('return_date')->count(),
            'overdue_rentals' => $customer->rentals()
                ->whereNull('return_date')
                ->whereRaw('DATE_ADD(rental_date, INTERVAL (SELECT rental_duration FROM film WHERE film.film_id = inventory.film_id) DAY) < NOW()')
                ->count(),
            'total_spent' => $customer->payments()->sum('amount'),
        ];

        return view('admin.customers.show', compact('customer', 'stats'));
    }

    /**
     * Formulario para editar un cliente
     */
    public function edit(Customer $customer)
    {
        $customer->load('store', 'address');
        $stores = Store::all();

        return view('admin.customers.edit', compact('customer', 'stores'));
    }

    /**
     * Actualizar información de un cliente
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => "required|email|max:50|unique:customer,email,{$customer->customer_id},customer_id",
            'store_id' => 'required|exists:store,store_id',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer->customer_id)
            ->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Cambiar estado activo/inactivo de un cliente
     */
    public function toggleActive(Customer $customer)
    {
        // Verificar que no tenga rentas activas si se va a desactivar
        if (!$customer->active && $customer->rentals()->whereNull('return_date')->count() > 0) {
            return redirect()->back()
                ->with('error', 'No se puede desactivar un cliente con rentas activas');
        }

        $customer->update(['active' => !$customer->active]);

        $status = $customer->active ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "Cliente {$status} exitosamente");
    }

    /**
     * Resetear contraseña de un cliente
     */
    public function resetPassword(Customer $customer)
    {
        // Generar nueva contraseña temporal
        $newPassword = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $customer->update(['password' => Hash::make($newPassword)]);

        // TODO: Enviar email con nueva contraseña temporal
        // Mail::send('emails.customer-password-reset', [...], function($message) { ... });

        return redirect()->back()
            ->with('success', 'Contraseña reseteada exitosamente')
            ->with('new_password', $newPassword);
    }

    /**
     * Listar clientes de una tienda específica
     */
    public function byStore(Store $store)
    {
        $customers = $store->customers()
            ->with('address')
            ->paginate(15);

        return view('admin.customers.by-store', compact('store', 'customers'));
    }

    /**
     * Eliminar un cliente
     */
    public function destroy(Customer $customer)
    {
        // Verificar que no tenga rentas activas
        if ($customer->rentals()->whereNull('return_date')->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'No se puede eliminar un cliente con rentas activas');
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }
}