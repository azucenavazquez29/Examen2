<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    protected $fillable = [
        'store_id',
        'first_name',
        'last_name',
        'email',
        'address_id',
        'active',
        'create_date'
    ];

    protected $casts = [
        'active' => 'boolean',
        'create_date' => 'datetime',
        'last_update' => 'timestamp'
    ];

    /**
     * Relación: Tienda a la que pertenece
     */
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    /**
     * Relación: Dirección
     */
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    /**
     * Relación: Rentas del cliente
     */
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id', 'customer_id');
    }

    /**
     * Relación: Pagos del cliente
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id', 'customer_id');
    }

    /**
     * Atributo: Nombre completo
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Obtener rentas activas
     */
    public function activeRentals()
    {
        return $this->rentals()
            ->whereNull('return_date')
            ->with(['inventory.film', 'staff'])
            ->get();
    }

    /**
     * Obtener rentas vencidas
     */
    public function overdueRentals()
    {
        return $this->rentals()
            ->whereNull('return_date')
            ->whereRaw('DATE_ADD(rental_date, INTERVAL (SELECT rental_duration FROM film WHERE film.film_id = inventory.film_id) DAY) < NOW()')
            ->with(['inventory.film', 'staff'])
            ->get();
    }

    /**
     * Verificar si tiene rentas vencidas
     */
    public function hasOverdueRentals()
    {
        return $this->overdueRentals()->count() > 0;
    }

    /**
     * Obtener total gastado
     */
    public function totalSpent()
    {
        return $this->payments()->sum('amount');
    }

    /**
     * Obtener historial de rentas
     */
    public function rentalHistory($limit = 10)
    {
        return $this->rentals()
            ->with(['inventory.film', 'staff'])
            ->orderBy('rental_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Contar rentas del cliente
     */
    public function rentalCount()
    {
        return $this->rentals()->count();
    }

    /**
     * Verificar si es cliente activo
     */
    public function isActive()
    {
        return $this->active === true;
    }

    /**
     * Obtener estado del cliente
     */
    public function getStatus()
    {
        if (!$this->active) {
            return 'Inactivo';
        }

        return 'Activo';
    }

    /**
     * Obtener rentas del mes actual
     */
    public function monthlyRentalsCount()
    {
        return $this->rentals()
            ->whereMonth('rental_date', now()->month)
            ->whereYear('rental_date', now()->year)
            ->count();
    }

    /**
     * Obtener películas favoritas (más rentadas)
     */
    public function favoriteFilms($limit = 5)
    {
        return $this->rentals()
            ->select('inventory.film_id')
            ->join('inventory', 'rental.inventory_id', '=', 'inventory.inventory_id')
            ->groupBy('inventory.film_id')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->with('inventory.film')
            ->get();
    }
}