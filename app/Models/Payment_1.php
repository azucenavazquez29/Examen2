<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment_1 extends Model
{
    protected $table = 'payment';
    protected $primaryKey = 'payment_id';
    public $timestamps = false;
    
    protected $fillable = ['customer_id', 'staff_id', 'rental_id', 'amount', 'payment_date'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }
    
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}