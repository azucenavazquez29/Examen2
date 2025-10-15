<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Address;

class EmpleadoController extends Controller
{


    /**
     * Dashboard principal del empleado
     */
    public function index()
    {
        $staffId = Session::get('staff_id');
        $storeId = Session::get('store_id');

        // Obtener información del empleado
        $staff = DB::table('staff')
            ->select('staff.*', 'store.store_id', 'address.address', 'city.city', 'country.country')
            ->join('store', 'staff.store_id', '=', 'store.store_id')
            ->join('address', 'staff.address_id', '=', 'address.address_id')
            ->join('city', 'address.city_id', '=', 'city.city_id')
            ->join('country', 'city.country_id', '=', 'country.country_id')
            ->where('staff.staff_id', $staffId)
            ->first();

        // Estadísticas de la sucursal
        $stats = [
            'total_clientes' => DB::table('customer')->where('store_id', $storeId)->count(),
            'rentas_activas' => DB::table('rental')
                ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
                ->where('inventory.store_id', $storeId)
                ->whereNull('rental.return_date')
                ->count(),
            'peliculas_disponibles' => DB::table('inventory')
                ->where('store_id', $storeId)
                ->count(),
            'ingresos_mes' => DB::table('payment')
                ->join('rental', 'payment.rental_id', '=', 'rental.rental_id')
                ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
                ->where('inventory.store_id', $storeId)
                ->whereMonth('payment.payment_date', date('m'))
                ->whereYear('payment.payment_date', date('Y'))
                ->sum('payment.amount')
        ];

        // Rentas recientes
        $rentasRecientes = DB::table('rental')
            ->select('rental.*', 'customer.first_name', 'customer.last_name', 'film.title')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('customer', 'rental.customer_id', '=', 'customer.customer_id')
            ->join('film', 'inventory.film_id', '=', 'film.film_id')
            ->where('inventory.store_id', $storeId)
            ->orderBy('rental.rental_date', 'desc')
            ->limit(10)
            ->get();

        return view('empleado.dashboard', compact('staff', 'stats', 'rentasRecientes'));
    }

    /**
     * Gestión de clientes - Listar
     */
    public function clientes(Request $request)
    {
        $storeId = Session::get('store_id');
        $search = $request->get('search', '');

        $query = DB::table('customer')
            ->select('customer.*', 'address.address', 'address.phone', 'city.city')
            ->join('address', 'customer.address_id', '=', 'address.address_id')
            ->join('city', 'address.city_id', '=', 'city.city_id')
            ->where('customer.store_id', $storeId);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('customer.first_name', 'like', "%{$search}%")
                  ->orWhere('customer.last_name', 'like', "%{$search}%")
                  ->orWhere('customer.email', 'like', "%{$search}%");
            });
        }

        $clientes = $query->paginate(15);

        return view('empleado.clientes.index', compact('clientes', 'search'));
    }

    /**
     * Crear nuevo cliente
     */
    public function crearCliente()
    {
                $addresses = Address::all();
        $ciudades = DB::table('city')
            ->select('city.*', 'country.country')
            ->join('country', 'city.country_id', '=', 'country.country_id')
            ->orderBy('country.country')
            ->orderBy('city.city')
            ->get();

        return view('empleado.clientes.crear', compact('ciudades','addresses'));
    }

    /**
     * Guardar nuevo cliente
     */
    public function guardarCliente(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email|max:50|unique:customer,email',
            'address' => 'required|string|max:50',
            'district' => 'required|string|max:20',
            'city_id' => 'required|integer|exists:city,city_id',
            'phone' => 'required|string|max:20',
            'postal_code' => 'nullable|string|max:10'
        ], [
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'first_name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'El apellido es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.'
        ]);

        try {
            DB::beginTransaction();

            // Crear dirección
            $addressId = DB::table('address')->insertGetId([
                'address' => $request->address,
                'address2' => $request->address2 ?? null,
                'district' => $request->district,
                'city_id' => $request->city_id,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'last_update' => now()
            ]);

            // Crear cliente
            $customerId = DB::table('customer')->insertGetId([
                'store_id' => Session::get('store_id'),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'address_id' => $addressId,
                'active' => 1,
                'create_date' => now(),
                'last_update' => now()
            ]);

            DB::commit();

            return redirect()->route('empleado.clientes')
                ->with('success', 'Cliente registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al registrar el cliente: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Editar cliente
     */
    public function editarCliente($id)
    {
         $addresses = Address::all();
        $storeId = Session::get('store_id');

        $cliente = DB::table('customer')
            ->select('customer.*', 'address.*', 'customer.customer_id', 'customer.email as customer_email')
            ->join('address', 'customer.address_id', '=', 'address.address_id')
            ->where('customer.customer_id', $id)
            ->where('customer.store_id', $storeId)
            ->first();

        if (!$cliente) {
            return redirect()->route('empleado.clientes')
                ->with('error', 'Cliente no encontrado o no pertenece a su sucursal.');
        }

        $ciudades = DB::table('city')
            ->select('city.*', 'country.country')
            ->join('country', 'city.country_id', '=', 'country.country_id')
            ->orderBy('country.country')
            ->orderBy('city.city')
            ->get();

        return view('empleado.clientes.editar', compact('cliente', 'ciudades','addresses'));
    }

    /**
     * Actualizar cliente
     */
    public function actualizarCliente(Request $request, $id)
    {
        $storeId = Session::get('store_id');

        // Verificar que el cliente pertenece a la sucursal
        $cliente = DB::table('customer')
            ->where('customer_id', $id)
            ->where('store_id', $storeId)
            ->first();

        if (!$cliente) {
            return redirect()->route('empleado.clientes')
                ->with('error', 'Cliente no encontrado.');
        }

        $request->validate([
            'first_name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|email|max:50|unique:customer,email,' . $id . ',customer_id',
            'address' => 'required|string|max:50',
            'district' => 'required|string|max:20',
            'city_id' => 'required|integer|exists:city,city_id',
            'phone' => 'required|string|max:20',
            'postal_code' => 'nullable|string|max:10'
        ]);

        try {
            DB::beginTransaction();

            // Actualizar dirección
            DB::table('address')
                ->where('address_id', $cliente->address_id)
                ->update([
                    'address' => $request->address,
                    'address2' => $request->address2 ?? null,
                    'district' => $request->district,
                    'city_id' => $request->city_id,
                    'postal_code' => $request->postal_code,
                    'phone' => $request->phone,
                    'last_update' => now()
                ]);

            // Actualizar cliente
            DB::table('customer')
                ->where('customer_id', $id)
                ->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'last_update' => now()
                ]);

            DB::commit();

            return redirect()->route('empleado.clientes')
                ->with('success', 'Cliente actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar el cliente.'])
                ->withInput();
        }
    }

    /**
     * Ver historial de rentas de un cliente
     */
    public function historialCliente($id)
    {
        $storeId = Session::get('store_id');

        $cliente = DB::table('customer')
            ->where('customer_id', $id)
            ->where('store_id', $storeId)
            ->first();

        if (!$cliente) {
            return redirect()->route('empleado.clientes')
                ->with('error', 'Cliente no encontrado.');
        }

        // Historial de rentas
        $rentas = DB::table('rental')
            ->select(
                'rental.*',
                'film.title',
                'film.rental_rate',
                'film.rental_duration',
                'payment.amount',
                'payment.payment_date'
            )
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('film', 'inventory.film_id', '=', 'film.film_id')
            ->leftJoin('payment', 'rental.rental_id', '=', 'payment.rental_id')
            ->where('rental.customer_id', $id)
            ->where('inventory.store_id', $storeId)
            ->orderBy('rental.rental_date', 'desc')
            ->get();

        // Después de obtener $rentas
        $rentas = $rentas->map(function($renta) {
            // Calcular días de retraso
            $fechaDevolucion = $renta->return_date ? \Carbon\Carbon::parse($renta->return_date) : now();
            $diasRenta = \Carbon\Carbon::parse($renta->rental_date)->diffInDays($fechaDevolucion);
            $diasRetraso = max(0, $diasRenta - $renta->rental_duration);
            
            // Calcular cargo por retraso (por ejemplo, $1.00 por día de retraso)
            $renta->dias_retraso = $diasRetraso;
            $renta->cargo_retraso = $diasRetraso * 1.00; // $1 por día de retraso
            
            return $renta;
        });

        // Actualizar total gastado incluyendo cargos por retraso
        $totalCargosRetraso = $rentas->sum('cargo_retraso');

        // Calcular totales
        $totalGastado = DB::table('payment')
            ->join('rental', 'payment.rental_id', '=', 'rental.rental_id')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->where('rental.customer_id', $id)
            ->where('inventory.store_id', $storeId)
            ->sum('payment.amount');

        return view('empleado.clientes.historial', compact('cliente', 'rentas', 'totalGastado'));
    }

    /**
     * Gestión de inventario
     */
   /**
 * Gestión de inventario
 */
public function inventario(Request $request)
{
    $storeId = Session::get('store_id');
    $search = $request->get('search', '');
    $category = $request->get('category', '');
    $actor = $request->get('actor', '');
    $language = $request->get('language', '');

    $query = DB::table('inventory')
        ->select(
            'film.film_id',
            'film.title',
            'film.rating',
            'film.rental_rate',
            'category.name as category',
            'language.name as language_name',
            DB::raw('COUNT(DISTINCT inventory.inventory_id) as total_copias'),
            DB::raw('SUM(CASE WHEN rental.return_date IS NULL THEN 1 ELSE 0 END) as copias_rentadas'),
            DB::raw('COUNT(DISTINCT inventory.inventory_id) - SUM(CASE WHEN rental.return_date IS NULL THEN 1 ELSE 0 END) as copias_disponibles')
        )
        ->join('film', 'inventory.film_id', '=', 'film.film_id')
        ->join('language', 'film.language_id', '=', 'language.language_id')
        ->leftJoin('film_category', 'film.film_id', '=', 'film_category.film_id')
        ->leftJoin('category', 'film_category.category_id', '=', 'category.category_id')
        ->leftJoin('rental', function($join) {
            $join->on('inventory.inventory_id', '=', 'rental.inventory_id')
                 ->whereNull('rental.return_date');
        })
        ->where('inventory.store_id', $storeId);

    // Filtro por búsqueda de título
    if ($search) {
        $query->where('film.title', 'like', "%{$search}%");
    }

    // Filtro por categoría
    if ($category) {
        $query->where('category.name', $category);
    }

    // Filtro por actor
    if ($actor) {
        $query->join('film_actor', 'film.film_id', '=', 'film_actor.film_id')
              ->where('film_actor.actor_id', $actor);
    }

    // Filtro por idioma
    if ($language) {
        $query->where('film.language_id', $language);
    }

    $peliculas = $query->groupBy(
            'film.film_id', 
            'film.title', 
            'film.rating', 
            'film.rental_rate', 
            'category.name',
            'language.name'
        )
        ->orderBy('film.title')
        ->paginate(20);

    // Obtener categorías para el filtro
    $categorias = DB::table('category')
        ->orderBy('name')
        ->get();

    // Obtener actores para el filtro
    $actores = DB::table('actor')
        ->select('actor_id', DB::raw("CONCAT(first_name, ' ', last_name) as full_name"))
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

    // Obtener idiomas para el filtro
    $idiomas = DB::table('language')
        ->orderBy('name')
        ->get();

    return view('empleado.inventario.index', compact(
        'peliculas', 
        'categorias', 
        'actores', 
        'idiomas', 
        'search', 
        'category', 
        'actor', 
        'language'
    ));
}

    /**
     * Ver detalles y copias de una película
     */
    public function detallesPelicula($id)
    {
        $storeId = Session::get('store_id');

        $pelicula = DB::table('film')
            ->select('film.*', 'category.name as category', 'language.name as language')
            ->leftJoin('film_category', 'film.film_id', '=', 'film_category.film_id')
            ->leftJoin('category', 'film_category.category_id', '=', 'category.category_id')
            ->join('language', 'film.language_id', '=', 'language.language_id')
            ->where('film.film_id', $id)
            ->first();

        if (!$pelicula) {
            return redirect()->route('empleado.inventario')
                ->with('error', 'Película no encontrada.');
        }

        // Obtener copias en esta sucursal
        $copias = DB::table('inventory')
            ->select(
                'inventory.*',
                'rental.rental_id',
                'rental.rental_date',
                'rental.return_date',
                'customer.first_name',
                'customer.last_name'
            )
            ->leftJoin('rental', function($join) {
                $join->on('inventory.inventory_id', '=', 'rental.inventory_id')
                     ->whereNull('rental.return_date');
            })
            ->leftJoin('customer', 'rental.customer_id', '=', 'customer.customer_id')
            ->where('inventory.film_id', $id)
            ->where('inventory.store_id', $storeId)
            ->get();

        // Historial de movimientos
        $historial = DB::table('rental')
            ->select('rental.*', 'customer.first_name', 'customer.last_name', 'inventory.inventory_id')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->join('customer', 'rental.customer_id', '=', 'customer.customer_id')
            ->where('inventory.film_id', $id)
            ->where('inventory.store_id', $storeId)
            ->orderBy('rental.rental_date', 'desc')
            ->limit(50)
            ->get();

        return view('empleado.inventario.detalles', compact('pelicula', 'copias', 'historial'));
    }


/**
 * Ver historial de accesos de todos los empleados (solo para managers)
 */
public function historialAccesos(Request $request)
{
    $storeId = Session::get('store_id');
    $filtroStaff = $request->get('staff_id', '');
    $fechaDesde = $request->get('fecha_desde', '');
    $fechaHasta = $request->get('fecha_hasta', '');

    $query = DB::table('staff_access_log')
        ->select(
            'staff_access_log.*',
            'staff.first_name',
            'staff.last_name',
            'staff.username',
            'store.store_id'
        )
        ->join('staff', 'staff_access_log.staff_id', '=', 'staff.staff_id')
        ->join('store', 'staff.store_id', '=', 'store.store_id')
        ->where('staff.store_id', $storeId);

    if ($filtroStaff) {
        $query->where('staff.staff_id', $filtroStaff);
    }

    if ($fechaDesde) {
        $query->where('staff_access_log.login_time', '>=', $fechaDesde);
    }

    if ($fechaHasta) {
        $query->where('staff_access_log.login_time', '<=', $fechaHasta . ' 23:59:59');
    }

    $accesos = $query->orderBy('staff_access_log.login_time', 'desc')
        ->paginate(50);

    // Lista de empleados para el filtro
    $empleados = DB::table('staff')
        ->where('store_id', $storeId)
        ->where('active', 1)
        ->get();

    // Estadísticas
    $stats = [
        'total_accesos' => DB::table('staff_access_log')
            ->join('staff', 'staff_access_log.staff_id', '=', 'staff.staff_id')
            ->where('staff.store_id', $storeId)
            ->count(),
        'accesos_hoy' => DB::table('staff_access_log')
            ->join('staff', 'staff_access_log.staff_id', '=', 'staff.staff_id')
            ->where('staff.store_id', $storeId)
            ->whereDate('staff_access_log.login_time', today())
            ->count(),
        'accesos_mes' => DB::table('staff_access_log')
            ->join('staff', 'staff_access_log.staff_id', '=', 'staff.staff_id')
            ->where('staff.store_id', $storeId)
            ->whereMonth('staff_access_log.login_time', date('m'))
            ->whereYear('staff_access_log.login_time', date('Y'))
            ->count(),
    ];

    return view('empleado.historial-accesos', compact(
        'accesos', 
        'empleados', 
        'stats', 
        'filtroStaff', 
        'fechaDesde', 
        'fechaHasta'
    ));
}

/**
 * Ver mis propios accesos
 */
public function misAccesos()
{
    $staffId = Session::get('staff_id');

    $accesos = DB::table('staff_access_log')
        ->where('staff_id', $staffId)
        ->orderBy('login_time', 'desc')
        ->paginate(30);

    $stats = [
        'total_accesos' => DB::table('staff_access_log')
            ->where('staff_id', $staffId)
            ->count(),
        'accesos_mes' => DB::table('staff_access_log')
            ->where('staff_id', $staffId)
            ->whereMonth('login_time', date('m'))
            ->whereYear('login_time', date('Y'))
            ->count(),
        'ultimo_acceso' => DB::table('staff_access_log')
            ->where('staff_id', $staffId)
            ->where('access_type', 'login')
            ->orderBy('login_time', 'desc')
            ->skip(1)
            ->first(),
    ];

    return view('empleado.mis-accesos', compact('accesos', 'stats'));
}

}