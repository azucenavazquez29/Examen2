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

    protected $hidden = [
        'password',
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

        /**
     * Relación: Pagos procesados
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'staff_id', 'staff_id');
    }

    /**
     * Relación: Tienda que gestiona (si es gerente)
     */
    public function managedStore()
    {
        return $this->hasOne(Store::class, 'manager_staff_id', 'staff_id');
    }

  /**
     * Verificar si es gerente
     */
    public function isManager()
    {
        return $this->managedStore()->exists();
    }

    /**
     * Verificar si está activo
     */
    public function isActive()
    {
        return $this->active === true;
    }

    /**
     * Obtener el estado del empleado
     */
    public function getStatus()
    {
        if (!$this->active) {
            return 'Inactivo';
        }

        if ($this->isManager()) {
            return 'Gerente';
        }

        return 'Empleado';
    }

    /**
     * Contar rentas del mes actual
     */
    public function monthlyRentalsCount()
    {
        return $this->rentals()
            ->whereMonth('rental_date', now()->month)
            ->whereYear('rental_date', now()->year)
            ->count();
    }

    /**
     * Obtener ingresos del mes
     */
    public function monthlyIncome()
    {
        return $this->payments()
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');
    }

}