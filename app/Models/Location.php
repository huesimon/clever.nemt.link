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

    public function scopeFavorited($query, $user = null)
    {
        return $query->whereHas('subscribers', function ($query) use($user) {
            $query->where('user_id', $user ? $user->id : auth()->id());
        });
    }

    public function getAvailableChargersCountAttribute()
    {
        return $this->is_public_visible == 'InProximity' ? 'N/a' : $this->chargers()->available()->count();
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
        return $this->is_public_visible == 'Always';
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
