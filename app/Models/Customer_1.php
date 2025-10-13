<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer_1 extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
    
    protected $fillable = ['store_id', 'first_name', 'last_name', 'email', 'address_id', 'active'];
    
    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }
    
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'customer_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'customer_id');
    }
    
    public function hasOverdueRentals()
    {
        return $this->rentals()
            ->whereNull('return_date')
            ->get()
            ->filter(function($rental) {
                return $rental->isOverdue();
            })
            ->count() > 0;
    }
}