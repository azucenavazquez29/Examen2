<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $primaryKey = 'staff_id';
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'address_id',
        'email',
        'store_id',
        'active',
        'username',
        'password'
    ];

    protected $casts = [
        'active' => 'boolean',
        'last_update' => 'timestamp'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id', 'address_id');
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'staff_id', 'staff_id');
    }

    /**
     * Nombre completo
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}