<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Staff;
use App\Models\Address;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Listar todas las tiendas
     */
    public function index()
    {
        $stores = Store::with(['manager', 'address', 'staff', 'customers'])
            ->paginate(10);
        
        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Mostrar detalle de una tienda
     */
    public function show(Store $store)
    {
        $store->load(['manager', 'address', 'staff' => function($q) {
            $q->where('active', true);
        }, 'customers' => function($q) {
            $q->where('active', true);
        }]);
        
        $stats = [
            'total_staff' => $store->staff->count(),
            'active_staff' => $store->staff->where('active', true)->count(),
            'total_customers' => $store->customers->count(),
            'active_customers' => $store->customers->where('active', true)->count(),
            'total_inventory' => $store->totalInventory(),
            'total_films' => $store->totalFilms(),
            'active_rentals' => $store->rentals()->whereNull('return_date')->count(),
            'overdue_rentals' => $store->overdueRentals()->count(),
        ];
        
        return view('admin.stores.show', compact('store', 'stats'));
    }

    /**
     * Formulario para crear una tienda
     */
    public function create()
    {
        $managers = Staff::where('active', true)
            ->doesntHave('managedStore')
            ->get();
        
        return view('admin.stores.create', compact('managers'));
    }

    /**
     * Guardar una nueva tienda
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'manager_staff_id' => 'required|exists:staff,staff_id|unique:store,manager_staff_id',
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

        // Crear tienda
        $store = Store::create([
            'manager_staff_id' => $validated['manager_staff_id'],
            'address_id' => $address->address_id,
        ]);

        // Asignar la tienda al gerente
        Staff::find($validated['manager_staff_id'])->update([
            'store_id' => $store->store_id
        ]);

        return redirect()->route('stores.show', $store->store_id)
            ->with('success', 'Tienda creada exitosamente');
    }

    /**
     * Formulario para editar una tienda
     */
    public function edit(Store $store)
    {
        $store->load('manager', 'address');
        
        $managers = Staff::where('active', true)
            ->where('store_id', $store->store_id)
            ->get();
        
        return view('admin.stores.edit', compact('store', 'managers'));
    }

    /**
     * Actualizar información de una tienda
     */
    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:50',
            'address2' => 'nullable|string|max:50',
            'district' => 'required|string|max:20',
            'city_id' => 'required|exists:city,city_id',
            'postal_code' => 'nullable|string|max:10',
            'phone' => 'required|string|max:20',
        ]);

        // Actualizar dirección
        $store->address->update([
            'address' => $validated['address'],
            'address2' => $validated['address2'] ?? null,
            'district' => $validated['district'],
            'city_id' => $validated['city_id'],
            'postal_code' => $validated['postal_code'] ?? null,
            'phone' => $validated['phone'],
        ]);

        return redirect()->route('stores.show', $store->store_id)
            ->with('success', 'Tienda actualizada exitosamente');
    }

    /**
     * Asignar un nuevo gerente a la tienda
     */
    public function assignManager(Request $request, Store $store)
    {
        $validated = $request->validate([
            'manager_staff_id' => 'required|exists:staff,staff_id',
        ]);

        // Desasignar gerente anterior si existe
        if ($store->manager) {
            $store->manager->update(['store_id' => null]);
        }

        // Asignar nuevo gerente
        $newManager = Staff::find($validated['manager_staff_id']);
        $newManager->update(['store_id' => $store->store_id]);
        
        $store->update(['manager_staff_id' => $validated['manager_staff_id']]);

        return redirect()->route('stores.show', $store->store_id)
            ->with('success', 'Gerente asignado exitosamente');
    }

    /**
     * Eliminar una tienda
     */
    public function destroy(Store $store)
    {
        // Verificar que no haya empleados ni clientes activos
        if ($store->staff()->where('active', true)->count() > 0) {
            return redirect()->route('stores.index')
                ->with('error', 'No se puede eliminar la tienda con empleados activos');
        }

        if ($store->customers()->where('active', true)->count() > 0) {
            return redirect()->route('stores.index')
                ->with('error', 'No se puede eliminar la tienda con clientes activos');
        }

        // Eliminar dirección
        $address = $store->address;
        $store->delete();
        $address->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Tienda eliminada exitosamente');
    }
}