<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use App\Models\Address;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class OtroCustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['store', 'address.city.country'])->get();
        return view('customers_otro.index', compact('customers'));
    }

    public function create()
    {
        $cities = City::with('country')->get();
        $stores = Store::with('address.city')->get();
        return view('customers_otro.create', compact('stores', 'cities'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'nullable|email|max:255|unique:customer,email',
                'store_id' => 'required|integer|exists:store,store_id',
                'active' => 'nullable|boolean',
                // Datos de dirección
                'address' => 'required|string|max:255',
                'address2' => 'nullable|string|max:255',
                'district' => 'required|string|max:100',
                'city_id' => 'required|integer|exists:city,city_id',
                'postal_code' => 'nullable|string|max:10',
                'phone' => 'required|string|max:20',
            ]);

            DB::beginTransaction();

            // 1. Crear la dirección primero
            $address = Address::create([
                'address' => $validated['address'],
                'address2' => $validated['address2'] ?? null,
                'district' => $validated['district'],
                'city_id' => $validated['city_id'],
                'postal_code' => $validated['postal_code'] ?? null,
                'phone' => $validated['phone'],
                'last_update' => now(),
            ]);

            // 2. Crear el cliente con el address_id generado
            $customer = Customer::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'address_id' => $address->address_id,
                'store_id' => $validated['store_id'],
                'active' => $request->has('active') ? 1 : 0,
                'create_date' => now(),
                'last_update' => now(),
            ]);

            DB::commit();

            return redirect()->route('customers_otro.index')
                ->with('success', 'Cliente creado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear el cliente: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function show(Customer $customer)
    {
        $customer->load(['store', 'address.city.country']);
        return view('customers_otro.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        $cities = City::with('country')->get();
        $stores = Store::with('address.city')->get();
        $cliente = $customer->load(['address.city']);
        
        return view('customers_otro.edit', compact('cliente', 'stores', 'cities'));
    }

    public function update(Request $request, Customer $customer)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'nullable|email|max:255|unique:customer,email,' . $customer->customer_id . ',customer_id',
                'store_id' => 'required|integer|exists:store,store_id',
                'active' => 'nullable|boolean',
                // Datos de dirección
                'address' => 'required|string|max:255',
                'address2' => 'nullable|string|max:255',
                'district' => 'required|string|max:100',
                'city_id' => 'required|integer|exists:city,city_id',
                'postal_code' => 'nullable|string|max:10',
                'phone' => 'required|string|max:20',
            ]);

            DB::beginTransaction();

            // 1. Actualizar la dirección existente
            $customer->address->update([
                'address' => $validated['address'],
                'address2' => $validated['address2'] ?? null,
                'district' => $validated['district'],
                'city_id' => $validated['city_id'],
                'postal_code' => $validated['postal_code'] ?? null,
                'phone' => $validated['phone'],
                'last_update' => now(),
            ]);

            // 2. Actualizar el cliente
            $customer->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'store_id' => $validated['store_id'],
                'active' => $request->has('active') ? 1 : 0,
                'last_update' => now(),
            ]);

            DB::commit();

            return redirect()->route('customers_otro.index')
                ->with('success', 'Cliente actualizado exitosamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al actualizar el cliente: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            DB::beginTransaction();

            $addressId = $customer->address_id;
            
            // Eliminar el cliente
            $customer->delete();
            
            // Opcionalmente eliminar la dirección si no está siendo usada por otros clientes
            $addressInUse = Customer::where('address_id', $addressId)->exists();
            if (!$addressInUse && $addressId) {
                Address::find($addressId)?->delete();
            }

            DB::commit();

            return redirect()->route('customers_otro.index')
                ->with('success', 'Cliente eliminado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar cliente: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al eliminar el cliente: ' . $e->getMessage()]);
        }
    }
}