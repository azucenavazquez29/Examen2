<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film_normal extends Model
{
    protected $table = 'film'; // Nombre real de la tabla en Sakila
    protected $primaryKey = 'film_id';
    public $timestamps = false; // La tabla sakila no usa created_at/updated_at

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'language_id',
        'original_language_id',
        'rental_duration',
        'rental_rate',
        'length',
        'replacement_cost',
        'rating',
        'special_features'
        // 'last_update' lo dejamos fuera porque lo llena MySQL con CURRENT_TIMESTAMP
    ];

    protected $casts = [
        'special_features' => 'array', // Para manejar el campo SET como array
    ];

    // Relación con actores (pivot table film_actor)
    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'film_actor', 'film_id', 'actor_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function originalLanguage()
    {
        return $this->belongsTo(Language::class, 'original_language_id');
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'film_category', 'film_id', 'category_id');
    }

  

/**
 * Relación con rentas a través del inventario
 */
public function rentals()
{
    return $this->hasManyThrough(
        Rental::class,
        Inventory::class,
        'film_id', // Foreign key en inventory
        'inventory_id', // Foreign key en rental
        'film_id', // Local key en film
        'inventory_id' // Local key en inventory
    );
}



/**
 * Obtener la renta actual (si existe)
 */
public function currentRental()
{
    return $this->rentals()
        ->with('customer')
        ->whereNull('return_date')
        ->first();
}

    /**
     * Obtener rentas activas SOLO de una sucursal específica - PARA EMPLEADOS
     */
    public function activeRentalsInStore($storeId)
    {
        return $this->hasManyThrough(
            Rental::class,
            Inventory::class,
            'film_id',
            'inventory_id',
            'film_id',
            'inventory_id'
        )->whereNull('rental.return_date')
          ->where('inventory.store_id', $storeId) // FILTRO POR SUCURSAL
          ->with(['customer', 'inventory'])
          ->orderBy('rental.rental_date', 'desc');
    }

    public function returnFilm(Request $request, $rental)
{
    try {
        $rental = Rental::findOrFail($rental);
        
        // Validar que el empleado solo pueda devolver películas de su tienda
        $userRole = Session::get('user_role');
        $userStoreId = Session::get('store_id');
        
        if ($userRole !== 'admin') {
            // Verificar que el inventory pertenezca a la tienda del empleado
            $inventory = $rental->inventory;
            if ($inventory->store_id != $userStoreId) {
                return back()->withErrors([
                    'error' => 'No tienes permiso para devolver películas de otra sucursal.'
                ]);
            }
        }
        
        // Procesar la devolución
        $rental->return_date = now();
        $rental->last_update = now();
        $rental->save();
        
        return back()->with('success', 'Película devuelta exitosamente.');
        
    } catch (\Exception $e) {
        Log::error('Error al devolver película: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Error al procesar la devolución.']);
    }
}

public function overdueRentals()
{
    return $this->rentals()
        ->with('customer')
        ->whereNull('return_date')
        ->whereRaw('DATE_ADD(rental_date, INTERVAL rental_duration DAY) < NOW()')
        ->get();
}

public function hasOverdueRentals()
{
    return $this->overdueRentals()->count() > 0;
}


    // Relación con inventory
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'film_id', 'film_id');
    }

    // Método para obtener copias disponibles en una sucursal específica
    public function availableCopiesInStore($storeId)
    {
        return $this->inventory()
            ->where('store_id', $storeId)
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->count();
    }

    // Método para obtener total de copias en una sucursal específica
    public function totalCopiesInStore($storeId)
    {
        return $this->inventory()->where('store_id', $storeId)->count();
    }

    // Resto de tus métodos existentes...
    public function availableCopies()
    {
        return $this->inventory()
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->count();
    }

    public function totalCopies()
    {
        return $this->inventory()->count();
    }

    public function isAvailable()
    {
        return $this->availableCopies() > 0;
    }

    public function activeRentals()
    {
        return $this->hasManyThrough(
            Rental::class,
            Inventory::class,
            'film_id',
            'inventory_id',
            'film_id',
            'inventory_id'
        )->whereNull('return_date')->get();
    }


}
