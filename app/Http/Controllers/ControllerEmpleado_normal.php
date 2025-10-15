<?php
namespace App\Http\Controllers;

use App\Models\Film_filter;
use App\Models\Language;

use App\Models\Film_normal;
use App\Models\Actor;
use App\Models\Customer;
use App\Models\Staff;
use App\Models\Category; // Asegúrate de importar el modelo arriba
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class ControllerEmpleado_normal extends FilmController
{
    public function index()
    {
        // Obtener el rol y la sucursal del empleado en sesión
        $userRole = Session::get('user_role');
        $storeId = Session::get('store_id');

        $categories = Category::orderBy('name')->get();
        $languages = Language::orderBy('name')->get();
        $actors = Actor::orderBy('first_name')->get();

        // Obtener actores
        $actors = Actor::limit(10)->get();
        $actors_tst = $actors->take(8);

        // Obtener staff
        $staff = Staff::limit(1)->get();

        // **FILTRAR PELÍCULAS SEGÚN EL ROL**
        if ($userRole === 'admin') {
            // ADMIN: Ver todas las películas
            $films_tst = Film_normal::with(['inventory.rentals' => function($query) {
                $query->whereNull('return_date');
            }])->paginate(12);
        } else {
            // EMPLOYEE: Solo películas de su sucursal
            $films_tst = Film_normal::with(['inventory' => function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            }, 'inventory.rentals' => function($query) {
                $query->whereNull('return_date');
            }])
            ->whereHas('inventory', function($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->distinct()
            ->paginate(12);
        }

        // **FILTRAR CLIENTES SEGÚN EL ROL**
        if ($userRole === 'admin') {
            // ADMIN: Ver todos los clientes
            $customers = Customer::limit(70)->get();
        } else {
            // EMPLOYEE: Solo clientes de su sucursal
            $customers = Customer::where('store_id', $storeId)
                ->limit(70)
                ->get();
        }


        return view('empleado_normal', compact(
            'films_tst', 
            'customers', 
            'actors_tst', 
            'staff', 
            'userRole', 
            'storeId',
            'categories',
            'languages',
            'actors'
        ));

    }
}