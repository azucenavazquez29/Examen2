<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category'; // nombre real de la tabla
    protected $primaryKey = 'category_id'; // clave primaria

    public $timestamps = false; // sakila no usa created_at ni updated_at

    protected $fillable = ['name'];

    // Relación: una categoría puede tener muchas películas (a través de film_category)
    public function films()
    {
        return $this->belongsToMany(Film::class, 'film_category', 'category_id', 'film_id');
    }
}
