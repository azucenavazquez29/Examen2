<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';          // nombre de la tabla
    protected $primaryKey = 'customer_id';  // clave primaria
    public $timestamps = false;             // la tabla no usa created_at / updated_at

    protected $fillable = [
        'store_id',
        'first_name',
        'last_name',
        'email',
        'address_id',
        'active',
        'create_date'
    ];

    // Relación con rentals
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id', 'customer_id');
    }
}
