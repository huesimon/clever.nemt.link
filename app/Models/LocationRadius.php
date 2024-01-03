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
        return Location::with('address')->whereHas('address', function ($query) {
            $boundingBox = $this->getBoundingBox();
            $query->validRange()->whereRaw("ST_Distance_Sphere(POINT(?, ?), POINT(addresses.lng, addresses.lat)) <= ?", [$this->lng, $this->lat, $this->radius])
            ->whereBetween('lat', [$boundingBox['minLat'], $boundingBox['maxLat']])
            ->whereBetween('lng', [$boundingBox['minLng'], $boundingBox['maxLng']]);
        })
        ->when($createdAfter, function ($query) use ($createdAfter) {
            $query->where('locations.created_at', '>=', $createdAfter);
        })->get();
    }

    public function getBoundingBox()
    {
        return [
            'minLat' => $this->lat - $this->radius,
            'maxLat' => $this->lat + $this->radius,
            'minLng' => $this->lng - $this->radius,
            'maxLng' => $this->lng + $this->radius,
        ];
    }

    public function getRadiusForHumansAttribute()
    {
         return $this->radius / 1000 . ' km';
    }
}
