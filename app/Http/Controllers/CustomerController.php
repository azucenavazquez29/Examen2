<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use App\Models\Adress;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customers.index', compact('customers'));
    }

        public function create()
    {
        $stores = Store::all();
        $adress = Adress::all();

        return view('customers.create', compact('stores', 'adress'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'store_id' => 'required|integer',
        'first_name' => 'required|string|max:100',
        'last_name' => 'nullable|string|max:100',
        'email' => 'required|string|max:255',
        'adress_id' => 'required|integer',
        'active' => 'required|integer',
    ]);

    

    Customer::create($validated);

    return redirect()->route('customers.index')->with('success', 'Cliente creado creada.');
}

public function show(Customer $customer)
    {
        
        $customer->load(['stores', 'adress']);
        return view('customers.show', compact('customer'));
    }

public function edit(Customer $customer)
{
        $stores = Store::all();
        $adress = Adress::all();

    return view('customers.edit', compact('stores', 'adress'));
}

public function update(Request $request, Customer $customer)
{
    $validated = $request->validate([
        'store_id' => 'required|integer',
        'first_name' => 'required|string|max:100',
        'last_name' => 'nullable|string|max:100',
        'email' => 'required|string|max:255',
        'adress_id' => 'required|integer',
        'active' => 'required|integer',
    ]);


    $customer->update($validated);

    return redirect()->route('customers.index')->with('success', 'Cliente actualizada.');
}

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Cliente eliminada.');
    }

}
