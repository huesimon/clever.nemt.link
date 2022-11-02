<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charger extends Model
{
    use HasFactory;

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


    public function scopeAvailable($query)
    {
        return $query->where('status', 'Available')
            ->whereNotNull('connector_id');
    }

    public function scopePlugType($query, $value)
    {
        return $query->where('plug_type', $value);
    }
}
