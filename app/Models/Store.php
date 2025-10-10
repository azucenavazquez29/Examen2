<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store'; 
    protected $primaryKey = 'store_id';
    public $timestamps = false; 

    protected $fillable = [
        'manager_staff_id',
        'address_id',
    ];


    public function manager()
    {
        return $this->belongsTo(Staff::class, 'manager_staff_id', 'staff_id');
    }


    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }


    public function staff()
    {
        return $this->hasMany(Staff::class, 'store_id', 'store_id');
    }


    public function customers()
    {
        return $this->hasMany(Customer::class, 'store_id', 'store_id');
    }


    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'store_id', 'store_id');
    }


    public function rentals()
    {
        return $this->hasManyThrough(
            Rental::class,
            Inventory::class,
            'store_id',     
            'inventory_id',  
            'store_id',     
            'inventory_id'  
        );
    }

  
    public function payments()
    {
        return $this->hasManyThrough(
            Payment::class,
            Staff::class,
            'store_id',
            'staff_id',  
            'store_id',  
            'staff_id'   
        );
    }


    public function films()
    {
        return $this->hasManyThrough(
            Film::class,
            Inventory::class,
            'store_id',  
            'film_id',   
            'store_id', 
            'film_id'  
        )->distinct();
    }


    public function hasFilmInStock($filmId)
    {
        return $this->inventory()
            ->where('film_id', $filmId)
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->exists();
    }


    public function availableCopiesOfFilm($filmId)
    {
        return $this->inventory()
            ->where('film_id', $filmId)
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->count();
    }


    public function activeRentals()
    {
        return $this->rentals()
            ->with(['customer', 'inventory.film'])
            ->whereNull('return_date')
            ->get();
    }


    public function overdueRentals()
    {
        return $this->rentals()
            ->with(['customer', 'inventory.film'])
            ->whereNull('return_date')
            ->whereRaw('DATE_ADD(rental_date, INTERVAL (SELECT rental_duration FROM film WHERE film.film_id = inventory.film_id) DAY) < NOW()')
            ->get();
    }


    public function hasOverdueRentals()
    {
        return $this->overdueRentals()->count() > 0;
    }


    public function totalSales()
    {
        return $this->payments()->sum('amount');
    }


    public function salesByPeriod($startDate, $endDate)
    {
        return $this->payments()
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');
    }

 
    public function topCustomers($limit = 10)
    {
        return $this->customers()
            ->withCount('rentals')
            ->orderByDesc('rentals_count')
            ->limit($limit)
            ->get();
    }


    public function topRentedFilms($limit = 10)
    {
        return Film::select('film.*')
            ->join('inventory', 'film.film_id', '=', 'inventory.film_id')
            ->join('rental', 'inventory.inventory_id', '=', 'rental.inventory_id')
            ->where('inventory.store_id', $this->store_id)
            ->groupBy('film.film_id')
            ->orderByRaw('COUNT(rental.rental_id) DESC')
            ->limit($limit)
            ->get();
    }


    public function totalFilms()
    {
        return $this->inventory()->distinct('film_id')->count('film_id');
    }


    public function totalInventory()
    {
        return $this->inventory()->count();
    }

    public function activeStaffCount()
    {
        return $this->staff()->where('active', true)->count();
    }


    public function activeCustomersCount()
    {
        return $this->customers()->where('active', true)->count();
    }
}