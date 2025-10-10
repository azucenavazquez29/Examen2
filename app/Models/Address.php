<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $primaryKey = 'address_id';
    public $timestamps = false;

    protected $fillable = [
        'address',
        'address2',
        'district',
        'city_id',
        'postal_code',
        'phone',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'address_id', 'address_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'address_id', 'address_id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'address_id', 'address_id');
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->address2,
            $this->district,
            $this->city->city ?? null,
            $this->city->country->country ?? null,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    public function getCountry()
    {
        return $this->city?->country;
    }

    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeByDistrict($query, $district)
    {
        return $query->where('district', 'like', "%{$district}%");
    }

    public function scopeByPostalCode($query, $postalCode)
    {
        return $query->where('postal_code', $postalCode);
    }

    public function hasActiveCustomers()
    {
        return $this->customers()->where('active', true)->exists();
    }

    public function hasActiveStaff()
    {
        return $this->staff()->where('active', true)->exists();
    }

    public function totalCustomers()
    {
        return $this->customers()->count();
    }

    public function totalStaff()
    {
        return $this->staff()->count();
    }

    public function totalStores()
    {
        return $this->stores()->count();
    }
}