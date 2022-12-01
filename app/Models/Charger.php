<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charger extends Model
{
    use HasFactory;

    protected $touches = ['location'];

    const AVAILABLE = 'Available';
    const OCCUPIED = 'Occupied';
    const OUT_OF_ORDER = 'OutOfOrder';
    const PLANNED = 'Planned';
    const UNKNOWN = 'Unknown';

    const TYPE_2 = 'Type2';
    const CCS = 'CCS';
    const CHADEMO = 'CHAdeMO';

    const PLUG_TYPES = [
        self::TYPE_2,
        self::CCS,
        self::CHADEMO,
    ];

    protected static function booted()
    {
        static::addGlobalScope('available', function ($builder) {
            $builder->validConnector();
        });
    }

    public function scopeValidConnector($query)
    {
        return $query->whereNotNull('connector_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available');
    }

    public function scopePlugType($query, $value)
    {
        return $query->where('plug_type', $value);
    }

    public function getCurrentSessionAttribute()
    {
        return $this->attributes['status'] === self::OCCUPIED ? 'Occupied since: ' . $this?->updated_at->diffForHumans() : 'Last used: ' . $this->updated_at->diffForHumans(); ;
    }

    public function getIsOccupiedAttribute()
    {
         return $this->attributes['status'] === self::OCCUPIED;
    }
}
