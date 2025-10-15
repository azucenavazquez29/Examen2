<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use App\Models\Address;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $cities = City::all();
        $stores = Store::all();
        $addresses = Address::all();
        
        return view('customers.create', compact('stores', 'addresses', 'cities'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'email' => 'required|string|max:255|unique:customer,email',
                'address_id' => 'required|integer|exists:address,address_id',
            ]);

            // Asignar valores automáticos
            $validated['store_id'] = 2;
            $validated['active'] = 1;
            $validated['create_date'] = now();
            $validated['last_update'] = now();

            Customer::create($validated);
            
            return redirect()->route('empleado.clientes.index')->with('success', 'Cliente creado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error al crear cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el cliente: ' . $e->getMessage()])->withInput();
        }
    }

    public function show(Customer $customer)
    {
        $customer->load(['store', 'address']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $cities = City::all();
        $stores = Store::all();
        $addresses = Address::all();
        
        $cliente = $customer;
        
        return view('customers.edit', compact('cliente', 'stores', 'addresses', 'cities'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'email' => 'required|string|max:255|unique:customer,email,' . $customer->customer_id . ',customer_id',
                'address_id' => 'required|integer|exists:address,address_id',
            ]);

            // Mantener valores automáticos
            $updateData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'address_id' => $validated['address_id'],
                'store_id' => 1,
                'active' => 1,
                'last_update' => now()
            ];

            $customer->update($updateData);
            
            return redirect()->route('empleado.clientes.index')->with('success', 'Cliente actualizado exitosamente.');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al actualizar el cliente: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return redirect()->route('customers.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al eliminar el cliente: ' . $e->getMessage()]);
        }
    }
}