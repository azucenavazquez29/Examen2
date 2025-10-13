<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RentalController extends Controller
{
    /**
     * Mostrar el panel de gestión de rentas
     */
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        $storeId = $staff->store_id;

        // Obtener rentas activas de la sucursal
        $activeRentals = DB::table('rental as r')
            ->join('customer as c', 'r.customer_id', '=', 'c.customer_id')
            ->join('inventory as i', 'r.inventory_id', '=', 'i.inventory_id')
            ->join('film as f', 'i.film_id', '=', 'f.film_id')
            ->where('i.store_id', $storeId)
            ->whereNull('r.return_date')
            ->select(
                'r.rental_id',
                'r.rental_date',
                'r.customer_id',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as customer_name"),
                'f.title as film_title',
                'f.rental_duration',
                'f.rental_rate',
                DB::raw('DATE_ADD(r.rental_date, INTERVAL f.rental_duration DAY) as due_date'),
                DB::raw('DATEDIFF(NOW(), DATE_ADD(r.rental_date, INTERVAL f.rental_duration DAY)) as days_overdue')
            )
            ->orderBy('r.rental_date', 'desc')
            ->paginate(15);

        return view('employee.rentals.index', compact('activeRentals', 'storeId'));
    }

    /**
     * Mostrar formulario para nueva renta
     */
    public function create(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $storeId = $staff->store_id;
        $customerId = $request->input('customer_id');
        
        $customer = null;
        $hasOverdueCharges = false;
        
        if ($customerId) {
            // Obtener información del cliente
            $customer = DB::table('customer')
                ->where('customer_id', $customerId)
                ->where('store_id', $storeId)
                ->first();
            
            if ($customer) {
                // Verificar si tiene cargos vencidos
                $hasOverdueCharges = $this->customerHasOverdueCharges($customerId);
            }
        }

        // Obtener películas disponibles en la sucursal
        $availableFilms = DB::table('film as f')
            ->join('inventory as i', 'f.film_id', '=', 'i.film_id')
            ->leftJoin('rental as r', function($join) {
                $join->on('i.inventory_id', '=', 'r.inventory_id')
                     ->whereNull('r.return_date');
            })
            ->where('i.store_id', $storeId)
            ->whereNull('r.rental_id')
            ->select(
                'f.film_id',
                'f.title',
                'f.rental_rate',
                'f.rental_duration',
                DB::raw('COUNT(i.inventory_id) as available_copies')
            )
            ->groupBy('f.film_id', 'f.title', 'f.rental_rate', 'f.rental_duration')
            ->having('available_copies', '>', 0)
            ->orderBy('f.title')
            ->get();

        return view('employee.rentals.create', compact('availableFilms', 'customer', 'hasOverdueCharges', 'storeId'));
    }

    /**
     * Buscar cliente
     */
    public function searchCustomer(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        $search = $request->input('search');

        $customers = DB::table('customer as c')
            ->where('c.store_id', $staff->store_id)
            ->where(function($query) use ($search) {
                $query->where('c.first_name', 'LIKE', "%{$search}%")
                      ->orWhere('c.last_name', 'LIKE', "%{$search}%")
                      ->orWhere('c.email', 'LIKE', "%{$search}%");
            })
            ->select(
                'c.customer_id',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as name"),
                'c.email'
            )
            ->limit(10)
            ->get();

        return response()->json($customers);
    }

    /**
     * Registrar nueva renta
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customer,customer_id',
            'film_id' => 'required|exists:film,film_id'
        ]);

        $staff = Auth::guard('staff')->user();
        $storeId = $staff->store_id;
        $customerId = $request->customer_id;
        $filmId = $request->film_id;

        // Verificar que el cliente pertenece a esta sucursal
        $customer = DB::table('customer')
            ->where('customer_id', $customerId)
            ->where('store_id', $storeId)
            ->first();

        if (!$customer) {
            return back()->with('error', 'Cliente no encontrado en esta sucursal.');
        }

        // Verificar cargos vencidos
        if ($this->customerHasOverdueCharges($customerId)) {
            return back()->with('error', 'El cliente tiene cargos vencidos. Debe regularizar su situación antes de rentar.');
        }

        // Buscar inventario disponible
        $inventory = DB::table('inventory as i')
            ->leftJoin('rental as r', function($join) {
                $join->on('i.inventory_id', '=', 'r.inventory_id')
                     ->whereNull('r.return_date');
            })
            ->where('i.film_id', $filmId)
            ->where('i.store_id', $storeId)
            ->whereNull('r.rental_id')
            ->select('i.inventory_id')
            ->first();

        if (!$inventory) {
            return back()->with('error', 'No hay copias disponibles de esta película.');
        }

        try {
            // Registrar la renta
            DB::table('rental')->insert([
                'rental_date' => Carbon::now(),
                'inventory_id' => $inventory->inventory_id,
                'customer_id' => $customerId,
                'staff_id' => $staff->staff_id,
                'last_update' => Carbon::now()
            ]);

            return redirect()->route('employee.rentals.index')
                ->with('success', 'Renta registrada exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al registrar la renta: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario de devolución
     */
    public function returnForm($rentalId)
    {
        $staff = Auth::guard('staff')->user();
        
        $rental = DB::table('rental as r')
            ->join('customer as c', 'r.customer_id', '=', 'c.customer_id')
            ->join('inventory as i', 'r.inventory_id', '=', 'i.inventory_id')
            ->join('film as f', 'i.film_id', '=', 'f.film_id')
            ->where('r.rental_id', $rentalId)
            ->where('i.store_id', $staff->store_id)
            ->whereNull('r.return_date')
            ->select(
                'r.rental_id',
                'r.rental_date',
                'r.customer_id',
                DB::raw("CONCAT(c.first_name, ' ', c.last_name) as customer_name"),
                'c.email',
                'f.title as film_title',
                'f.rental_duration',
                'f.rental_rate',
                'f.replacement_cost',
                DB::raw('DATE_ADD(r.rental_date, INTERVAL f.rental_duration DAY) as due_date')
            )
            ->first();

        if (!$rental) {
            return redirect()->route('employee.rentals.index')
                ->with('error', 'Renta no encontrada o ya devuelta.');
        }

        // Calcular cargo por retraso
        $lateCharge = $this->calculateLateCharge($rental);

        return view('employee.rentals.return', compact('rental', 'lateCharge'));
    }

    /**
     * Procesar devolución
     */
    public function processReturn(Request $request, $rentalId)
    {
        $request->validate([
            'condition' => 'required|in:good,damaged,lost'
        ]);

        $staff = Auth::guard('staff')->user();
        
        $rental = DB::table('rental as r')
            ->join('inventory as i', 'r.inventory_id', '=', 'i.inventory_id')
            ->join('film as f', 'i.film_id', '=', 'f.film_id')
            ->where('r.rental_id', $rentalId)
            ->where('i.store_id', $staff->store_id)
            ->whereNull('r.return_date')
            ->select('r.*', 'f.rental_rate', 'f.rental_duration', 'f.replacement_cost', 'i.inventory_id')
            ->first();

        if (!$rental) {
            return redirect()->route('employee.rentals.index')
                ->with('error', 'Renta no encontrada.');
        }

        try {
            DB::beginTransaction();

            // Actualizar fecha de devolución
            DB::table('rental')
                ->where('rental_id', $rentalId)
                ->update([
                    'return_date' => Carbon::now(),
                    'last_update' => Carbon::now()
                ]);

            // Calcular cargo total
            $lateCharge = $this->calculateLateCharge($rental);
            $totalCharge = $rental->rental_rate;

            // Agregar cargo por retraso si aplica
            if ($lateCharge > 0) {
                $totalCharge += $lateCharge;
            }

            // Agregar cargo por daño o pérdida
            if ($request->condition === 'lost') {
                $totalCharge += $rental->replacement_cost;
            } elseif ($request->condition === 'damaged') {
                $totalCharge += ($rental->replacement_cost * 0.5); // 50% del costo de reemplazo
            }

            // Registrar pago
            DB::table('payment')->insert([
                'customer_id' => $rental->customer_id,
                'staff_id' => $staff->staff_id,
                'rental_id' => $rentalId,
                'amount' => $totalCharge,
                'payment_date' => Carbon::now(),
                'last_update' => Carbon::now()
            ]);

            DB::commit();

            return redirect()->route('employee.rentals.index')
                ->with('success', "Devolución procesada. Cargo total: $" . number_format($totalCharge, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar devolución: ' . $e->getMessage());
        }
    }

    /**
     * Verificar si el cliente tiene cargos vencidos
     */
    private function customerHasOverdueCharges($customerId)
    {
        $overdueRentals = DB::table('rental as r')
            ->join('inventory as i', 'r.inventory_id', '=', 'i.inventory_id')
            ->join('film as f', 'i.film_id', '=', 'f.film_id')
            ->where('r.customer_id', $customerId)
            ->whereNull('r.return_date')
            ->whereRaw('DATEDIFF(NOW(), DATE_ADD(r.rental_date, INTERVAL f.rental_duration DAY)) > 7')
            ->count();

        return $overdueRentals > 0;
    }

    /**
     * Calcular cargo por retraso
     */
    private function calculateLateCharge($rental)
    {
        $dueDate = Carbon::parse($rental->due_date);
        $now = Carbon::now();
        
        if ($now->gt($dueDate)) {
            $daysLate = $now->diffInDays($dueDate);
            // $1 por día de retraso
            return $daysLate * 1.00;
        }
        
        return 0;
    }
}