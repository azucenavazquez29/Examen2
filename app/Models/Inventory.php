<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventory';
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;

    protected $fillable = [
        'film_id',
        'store_id'
    ];

    public function film()
    {
        return $this->belongsTo(Film::class, 'film_id', 'film_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'inventory_id', 'inventory_id');
    }

    /**
     * Verificar si esta copia específica está disponible
     */
    public function isAvailable()
    {
        return !$this->rentals()->whereNull('return_date')->exists();
    }

    /**
     * Obtener la renta actual de esta copia
     */
    public function currentRental()
    {
        return $this->rentals()
            ->with('customer')
            ->whereNull('return_date')
            ->first();
    }
}
