<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Film;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Mostrar listado completo de inventario
     */
     public function index(Request $request)
    {
        $query = Inventory::with(['film', 'store'])
            ->select('inventory.*');

        // Filtros
        if ($request->filled('film_id')) {
            $query->where('film_id', $request->film_id);
        }

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->store_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('film', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        // Agrupación de inventario por película y tienda (CON LOS MISMOS FILTROS)
        $groupedQuery = Inventory::select(
                'film_id',
                'store_id',
                DB::raw('COUNT(*) as total_copies'),
                DB::raw('SUM(CASE WHEN inventory_id NOT IN (
                    SELECT inventory_id FROM rental 
                    WHERE return_date IS NULL
                ) THEN 1 ELSE 0 END) as available_copies')
            );

        // Aplicar los mismos filtros a la agrupación
        if ($request->filled('film_id')) {
            $groupedQuery->where('film_id', $request->film_id);
        }

        if ($request->filled('store_id')) {
            $groupedQuery->where('store_id', $request->store_id);
        }

        if ($request->filled('search')) {
            $groupedQuery->whereHas('film', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }

        $inventoryGrouped = $groupedQuery->groupBy('film_id', 'store_id')
            ->with(['film', 'store'])
            ->get();

        $inventory = $query->paginate(20)->appends($request->except('page'));
        $films = Film::orderBy('title')->get();
        $stores = Store::with('address.city')->get();

        return view('inventory.index', compact('inventory', 'inventoryGrouped', 'films', 'stores'));
    }


    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $films = Film::orderBy('title')->get();
        $stores = Store::with('address.city')->get();
        
        return view('inventory.create', compact('films', 'stores'));
    }

    /**
     * Guardar nueva copia de inventario
     */
    public function store(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:film,film_id',
            'store_id' => 'required|exists:store,store_id'
        ]);

        $inventory = Inventory::create([
            'film_id' => $request->film_id,
            'store_id' => $request->store_id
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Copia agregada al inventario correctamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Inventory $inventory)
    {
        $films = Film::orderBy('title')->get();
        $stores = Store::with('address.city')->get();
        
        return view('inventory.edit', compact('inventory', 'films', 'stores'));
    }

    /**
     * Actualizar inventario
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'film_id' => 'required|exists:film,film_id',
            'store_id' => 'required|exists:store,store_id'
        ]);

        // Verificar que no esté rentado actualmente
        $isRented = DB::table('rental')
            ->where('inventory_id', $inventory->inventory_id)
            ->whereNull('return_date')
            ->exists();

        if ($isRented) {
            return back()->with('error', 'No se puede modificar. Esta copia está actualmente rentada.');
        }

        $inventory->update([
            'film_id' => $request->film_id,
            'store_id' => $request->store_id
        ]);

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Inventario actualizado correctamente');
    }

    /**
     * Eliminar del inventario
     */
    public function destroy(Inventory $inventory)
    {
        // Verificar que no esté rentado actualmente
        $isRented = DB::table('rental')
            ->where('inventory_id', $inventory->inventory_id)
            ->whereNull('return_date')
            ->exists();

        if ($isRented) {
            return back()->with('error', 'No se puede eliminar. Esta copia está actualmente rentada.');
        }

        // Verificar si tiene historial de rentas
        $hasRentals = DB::table('rental')
            ->where('inventory_id', $inventory->inventory_id)
            ->exists();

        if ($hasRentals) {
            return back()->with('warning', 'Esta copia tiene historial de rentas. Considere mantenerla en el sistema.');
        }

        $inventory->delete();

        return redirect()
            ->route('inventory.index')
            ->with('success', 'Copia eliminada del inventario correctamente');
    }

    /**
     * Ver inventario por película
     */
    public function byFilm(Film $film)
    {
        $inventoryByStore = Inventory::where('film_id', $film->film_id)
            ->select(
                'store_id',
                DB::raw('COUNT(*) as total_copies'),
                DB::raw('SUM(CASE WHEN inventory_id NOT IN (
                    SELECT inventory_id FROM rental 
                    WHERE return_date IS NULL
                ) THEN 1 ELSE 0 END) as available_copies')
            )
            ->groupBy('store_id')
            ->with('store.address.city')
            ->get();

        $allInventory = Inventory::where('film_id', $film->film_id)
            ->with(['store.address.city'])
            ->get();

        return view('inventory.by-film', compact('film', 'inventoryByStore', 'allInventory'));
    }

    /**
     * Ver inventario por tienda
     */
    public function byStore(Store $store)
    {
        $inventoryByFilm = Inventory::where('store_id', $store->store_id)
            ->select(
                'film_id',
                DB::raw('COUNT(*) as total_copies'),
                DB::raw('SUM(CASE WHEN inventory_id NOT IN (
                    SELECT inventory_id FROM rental 
                    WHERE return_date IS NULL
                ) THEN 1 ELSE 0 END) as available_copies')
            )
            ->groupBy('film_id')
            ->with('film')
            ->get();

        return view('inventory.by-store', compact('store', 'inventoryByFilm'));
    }

    /**
     * Agregar copias en bulk (múltiples copias de una película)
     */
    public function bulkAdd(Request $request)
    {
        $request->validate([
            'film_id' => 'required|exists:film,film_id',
            'store_id' => 'required|exists:store,store_id',
            'quantity' => 'required|integer|min:1|max:50'
        ]);

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $request->quantity; $i++) {
                Inventory::create([
                    'film_id' => $request->film_id,
                    'store_id' => $request->store_id
                ]);
            }

            DB::commit();

            return redirect()
                ->route('inventory.index')
                ->with('success', "Se agregaron {$request->quantity} copias al inventario correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al agregar copias: ' . $e->getMessage());
        }
    }

    /**
     * Transferir copias entre tiendas
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'inventory_ids' => 'required|array',
            'inventory_ids.*' => 'exists:inventory,inventory_id',
            'target_store_id' => 'required|exists:store,store_id'
        ]);

        DB::beginTransaction();
        try {
            // Verificar que ninguna copia esté rentada
            $rentedCopies = DB::table('rental')
                ->whereIn('inventory_id', $request->inventory_ids)
                ->whereNull('return_date')
                ->count();

            if ($rentedCopies > 0) {
                return back()->with('error', 'Algunas copias seleccionadas están actualmente rentadas.');
            }

            // Realizar transferencia
            Inventory::whereIn('inventory_id', $request->inventory_ids)
                ->update(['store_id' => $request->target_store_id]);

            DB::commit();

            $count = count($request->inventory_ids);
            return redirect()
                ->route('inventory.index')
                ->with('success', "Se transfirieron {$count} copias correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al transferir copias: ' . $e->getMessage());
        }
    }

    /**
     * Obtener estadísticas de inventario (API para dashboard)
     */
    public function stats()
    {
        $stats = [
            'total_copies' => Inventory::count(),
            'available_copies' => Inventory::whereNotIn('inventory_id', function($query) {
                $query->select('inventory_id')
                    ->from('rental')
                    ->whereNull('return_date');
            })->count(),
            'rented_copies' => DB::table('rental')
                ->whereNull('return_date')
                ->distinct('inventory_id')
                ->count(),
            'by_store' => Inventory::select('store_id', DB::raw('COUNT(*) as total'))
                ->groupBy('store_id')
                ->with('store')
                ->get(),
            'low_stock_films' => Inventory::select('film_id', DB::raw('COUNT(*) as total'))
                ->groupBy('film_id')
                ->having('total', '<', 3)
                ->with('film')
                ->get()
        ];

        return response()->json($stats);
    }
}