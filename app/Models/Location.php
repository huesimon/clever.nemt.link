<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    use HasFactory;

    public function chargers()
    {
        return $this->hasMany(Charger::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class);
    }

    public function setCoordinatesAttribute($value)
    {
        $this->attributes['coordinates'] = DB::raw("POINT($value)");
    }
}
