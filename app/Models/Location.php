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

    public function newestCharger()
    {
        return $this->hasOne(Charger::class)->latest();
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class);
    }

    public function getAvailableChargersCountAttribute()
    {
        return $this->is_public_visable == 'InProximity' ? 'N/a' : $this->chargers()->available()->count();
    }

    public function getTotalChargersCountAttribute()
    {
        return $this->chargers()->count();
    }

    public function getIsOccupiedAttribute()
    {
        return $this->chargers()->where('status', '!=', Charger::AVAILABLE)->count() >= $this->chargers()->count();
    }

    public function getIsPublicAttribute()
    {
        return $this->is_public_visable == 'Always';
    }

    public function getNewestChargerUpdatedAtAttribute()
    {
        return $this->chargers()->max('updated_at');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
}
