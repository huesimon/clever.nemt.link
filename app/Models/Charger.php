<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charger extends Model
{
    use HasFactory;

    protected $primaryKey = 'evse_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $touches = ['location'];

    const AVAILABLE = 'Available';
    const OCCUPIED = 'Occupied';
    const OUT_OF_ORDER = 'OutOfOrder';
    const INOPERATIVE = 'Inoperative';
    const PLANNED = 'Planned';
    const UNKNOWN = 'Unknown';
    const BLOCKED = 'Blocked';

    const STATUSES = [
        self::AVAILABLE,
        self::OCCUPIED,
        self::OUT_OF_ORDER,
        self::INOPERATIVE,
        self::PLANNED,
        self::UNKNOWN,
        self::BLOCKED,
    ];

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
        return $this->belongsTo(Location::class, 'location_external_id', 'external_id');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', self::AVAILABLE);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', self::OCCUPIED);
    }

    public function scopeOutOfOrder($query)
    {
        return $query->where('status', self::OUT_OF_ORDER);
    }

    public function scopeInoperative($query)
    {
        return $query->where('status', self::INOPERATIVE);
    }

    public function scopePlanned($query)
    {
        return $query->where('status', self::PLANNED);
     }

    public function scopeUnknown($query)
    {
        return $query->where('status', self::UNKNOWN);
    }

    public function scopePlugType($query, $value)
    {
        return $query->where('plug_type', $value);
    }

    public function scopeCcs($query)
    {
        return $query->plugType(self::CCS);
    }

    public function scopeChademo($query)
    {
        return $query->plugType(self::CHADEMO);
    }

    public function scopeType2($query)
    {
        return $query->plugType(self::TYPE_2);
    }

    public function getCurrentSessionAttribute()
    {
        return match ($this->attributes['status']){
            self::OCCUPIED => 'Occupied since: ' . $this->updated_at->diffForHumans(),
            self::AVAILABLE => 'Last used: ' . $this->updated_at->diffForHumans(),
            self::OUT_OF_ORDER => 'Out of order since: ' . $this->updated_at->diffForHumans(),
            self::INOPERATIVE => 'Inoperative since: ' . $this->updated_at->diffForHumans(),
            default => 'Unknown: ' . $this->attributes['status'],
        };
    }

    public function getSessionColorAttribute()
    {
         return match ($this->attributes['status']){
            self::OCCUPIED => 'bg-gray-100',
            self::AVAILABLE => 'bg-indigo-100',
            self::OUT_OF_ORDER => 'bg-yellow-100',
            self::INOPERATIVE => 'bg-red-100',
            default => 'bg-gray-100',
         };
    }

    public function getReadableIdAttribute()
    {
        $id = str_replace('-', '.', $this->attributes['evse_connector_id']);

        return preg_replace('/[^0-9.]/', '', $id);

    }

    /**
     * Get the rounded max_power_kw
     */
    public function getKwAttribute()
    {
        return preg_replace('/\..*/', '', $this->attributes['max_power_kw']);
    }

    public function getKwColorAttribute()
    {
        return match (true) {
            $this->attributes['max_power_kw'] <= 40 => 'bg-green-100',
            $this->attributes['max_power_kw'] <= 100 => 'bg-yellow-100',
            $this->attributes['max_power_kw'] <= 200 => 'bg-orange-100',
            default => 'bg-red-100',
        };
    }

    public function getIsOccupiedAttribute()
    {
         return $this->attributes['status'] === self::OCCUPIED;
    }

    public function getMightBeOutOfOrderAttribute()
    {
         return $this->attributes['updated_at'] < now()->subMinutes(100)
            && $this->attributes['status'] === self::OCCUPIED
            && $this->attributes['max_power_kw'] > 40;
    }

    public function scopeMightBeOutOfOrder($query)
    {
        return $query->where('updated_at', '<', now()->subMinutes(100))
            ->where('status', self::OCCUPIED)
            ->where('max_power_kw', '>', 40);
    }

    public function getIsHighPowerChargerAttribute()
    {
         return $this->attributes['max_power_kw'] > 40;
    }
}
