<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $with = ['chargers'];

    use HasFactory;

    public function chargers()
    {
        return $this->hasMany(Charger::class);
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class);
    }

    public function getAvailableChargersCountAttribute()
    {
        return $this->chargers()->available()->count();
    }

    public function getTotalChargersCountAttribute()
    {
        return $this->chargers()->count();
    }

    public function getIsOccupiedAttribute()
    {
        return $this->chargers()->where('status', '!=', Charger::AVAILABLE)->count() >= $this->chargers()->count();
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
}
