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
        'last_update' => 'datetime',
    ];


    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id', 'customer_id');
    }



    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id', 'customer_id');
    }


    public function activeRentals()
    {
        return $this->rentals()
            ->with('inventory.film')
            ->whereNull('return_date')
            ->get();
    }

       /**
     * Atributo: Nombre completo
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


public function hasOverdueRentals()
{
    return $this->rentals()
        ->whereNull('return_date')
        ->whereHas('inventory.film', function($query) {
           
            $query->whereRaw('DATE_ADD(rental.rental_date, INTERVAL film.rental_duration DAY) < NOW()');
        })
        ->exists();
}

public function overdueRentals()
{
    return $this->rentals()
        ->with(['inventory.film'])
        ->whereNull('return_date')
        ->whereHas('inventory.film', function($query) {
            $query->whereRaw('DATE_ADD(rental.rental_date, INTERVAL film.rental_duration DAY) < NOW()');
        })
        ->get();
}

public function getRouteKeyName()
{
    return 'customer_id';
}
}