<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'country';
    protected $primaryKey = 'country_id';
    public $timestamps = false;

    protected $fillable = [
        'country',
    ];

    public function cities()
    {
        return $this->hasMany(City::class, 'country_id', 'country_id');
    }

    public function addresses()
    {
        return $this->hasManyThrough(
            Address::class,
            City::class,
            'country_id',
            'city_id',
            'country_id',
            'city_id'
        );
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            City::class,
            'country_id',
            'city_id',
            'country_id',
            'city_id'
        )->join('address', 'customer.address_id', '=', 'address.address_id');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('country', 'like', "%{$search}%");
    }

    public function totalCities()
    {
        return $this->cities()->count();
    }

    public function totalAddresses()
    {
        return $this->addresses()->count();
    }
}