<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationRadius extends Model
{
    use HasFactory;

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    public function locations($createdAfter = null)
    {
        return Location::whereHas('address', function ($query) {
            $query->whereRaw("ST_Distance_Sphere(POINT(?, ?), POINT(addresses.lng, addresses.lat)) <= ?", [$this->lng, $this->lat, $this->radius]);
        })
        ->when($createdAfter, function ($query) use ($createdAfter) {
            $query->where('locations.created_at', '>=', $createdAfter);
        })->get();
    }

    public function getRadiusForHumansAttribute()
    {
         return $this->radius / 1000 . ' km';
    }
}
