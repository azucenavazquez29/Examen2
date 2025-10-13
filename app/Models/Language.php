<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'language'; 
    protected $primaryKey = 'language_id';

    public $timestamps = false; 

    protected $fillable = ['name'];

        protected $casts = [
        'last_update' => 'datetime',
    ];



    public function films()
    {
        return $this->hasMany(Film::class, 'language_id', 'language_id');
    }

        public function originalLanguageFilms()
    {
        return $this->hasMany(Film::class, 'original_language_id', 'language_id');
    }
}
