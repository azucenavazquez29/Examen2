<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    protected $table = 'rental';
    protected $primaryKey = 'rental_id';
    public $timestamps = false; // Sakila usa last_update

    protected $fillable = [
        'rental_date',
        'inventory_id',
        'customer_id',
        'return_date',
        'staff_id'
    ];

    protected $casts = [
        'rental_date' => 'datetime',
        'return_date' => 'datetime',
        'last_update' => 'timestamp'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'inventory_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'staff_id');
    }

    /**
     * Verificar si está vencido
     */
    public function isOverdue()
    {
        if ($this->return_date) {
            return false; // Ya fue devuelto
        }

        $film = $this->inventory->film;
        $dueDate = $this->rental_date->addDays($film->rental_duration);
        
        return Carbon::now()->isAfter($dueDate);
    }

    /**
     * Días de retraso
     */
    public function daysOverdue()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $film = $this->inventory->film;
        $dueDate = $this->rental_date->addDays($film->rental_duration);
        
        return Carbon::now()->diffInDays($dueDate);
    }
}