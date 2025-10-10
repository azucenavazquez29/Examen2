<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'city';
    protected $primaryKey = 'city_id';
    public $timestamps = false;

    protected $fillable = [
        'city',
        'country_id',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'country_id');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'city_id', 'city_id');
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,
            Address::class,
            'city_id',
            'address_id',
            'city_id',
            'address_id'
        );
    }

    public function stores()
    {
        return $this->hasManyThrough(
            Store::class,
            Address::class,
            'city_id',
            'address_id',
            'city_id',
            'address_id'
        );
    }

    public function staff()
    {
        return $this->hasManyThrough(
            Staff::class,
            Address::class,
            'city_id',
            'address_id',
            'city_id',
            'address_id'
        );
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('city', 'like', "%{$search}%");
    }

    public function totalAddresses()
    {
        return $this->addresses()->count();
    }

    public function totalCustomers()
    {
        return $this->customers()->count();
    }

    public function totalStores()
    {
        return $this->stores()->count();
    }

    public function getFullLocationAttribute()
    {
        return $this->city . ', ' . ($this->country->country ?? '');
    }
}