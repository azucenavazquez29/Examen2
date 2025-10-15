<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Film_filter extends Model
{
    protected $table = 'film';
    protected $primaryKey = 'film_id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'language_id',
        'rental_duration',
        'rental_rate',
        'length',
        'replacement_cost',
        'rating',
        'special_features'
    ];

    // Relación con categorías (muchos a muchos)
    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'film_category',
            'film_id',
            'category_id'
        );
    }

    // Relación con actores (muchos a muchos)
    public function actors()
    {
        return $this->belongsToMany(
            Actor::class,
            'film_actor',
            'film_id',
            'actor_id'
        );
    }

    // Relación con idioma
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    // Relación con inventario
    public function inventory()
    {
        return $this->hasMany(Inventory::class, 'film_id');
    }

    // Métodos para calcular disponibilidad

    // Total de copias (todas las sucursales)
    public function totalCopies()
    {
        return $this->inventory()->count();
    }

    // Copias disponibles (todas las sucursales)
    public function availableCopies()
    {
        return $this->inventory()
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->count();
    }

    // Total de copias en una sucursal específica
    public function totalCopiesInStore($storeId)
    {
        return $this->inventory()
            ->where('store_id', $storeId)
            ->count();
    }

    // Copias disponibles en una sucursal específica
    public function availableCopiesInStore($storeId)
    {
        return $this->inventory()
            ->where('store_id', $storeId)
            ->whereDoesntHave('rentals', function($query) {
                $query->whereNull('return_date');
            })
            ->count();
    }
}