<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    public function addressable()
    {
        return $this->morphTo();
    }

    public function scopeValidRange($query)
    {
        return $query->whereBetween('lat', [-90, 90])->whereBetween('lng', [-180, 180]);
    }
}
