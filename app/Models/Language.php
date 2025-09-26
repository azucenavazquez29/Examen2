<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'language'; // nombre real de la tabla
    protected $primaryKey = 'language_id'; // clave primaria

    public $timestamps = false; // la tabla no usa created_at ni updated_at

    protected $fillable = ['name'];

    // Relación con Film (un idioma tiene muchas películas)
    public function films()
    {
        return $this->hasMany(Film::class, 'language_id');
    }
}
