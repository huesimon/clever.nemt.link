<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Location extends Model
{

    protected $primaryKey = 'external_id';
    protected $keyType = 'string';

    public $incrementing = false;

    protected $with = ['chargers'];

    use HasFactory;

    public function chargers()
    {
        return $this->hasMany(Charger::class, 'location_external_id', 'external_id');
    }

    public function availableChargers()
    {
        return $this->chargers()->available();
    }

    public function occupiedChargers()
    {
        return $this->chargers()->occupied();
    }

    public function outOfOrderChargers()
    {
        return $this->chargers()->outOfOrder();
    }

    public function inoperativeChargers()
    {
        return $this->chargers()->inoperative();
    }

    public function unknownChargers()
    {
        return $this->chargers()->unknown();
    }

    public function plannedChargers()
    {
        return $this->chargers()->planned();
    }

    public function blockedChargers()
    {
        return $this->chargers()->blocked();
    }

    public function subscribers()
    {
        return $this->belongsToMany(User::class);
    }

    public function history()
    {
        return $this->hasMany(LocationHistory::class, 'location_id', 'external_id');
    }

    /**
     * Is ->sum the correct function to use?
     */
    public function historyTimestamped($from = null, $to = null)
    {
        $from = $from ?? now()->subDays(30);
        $to = $to ?? now();

        return Cache::remember('location-history-timestamped-' .
            $from->format('Y-m-d') . '-' . $to->format('Y-m-d') . '-' .
            $this->external_id, now()->addMinutes(15), function () use ($from, $to) {
            Log::info('Fetching location history for ' . $this->external_id . ' from ' . $from->format('Y-m-d') . ' to ' . $to->format('Y-m-d'));
            return $this->history()
                ->whereBetween('created_at', [$from, $to])
                ->get()
                ->groupBy(function ($item, $key) {
                return $item->created_at->format('Y-m-d H:i');
            })->map(function ($item, $key) {
                return [
                    'occupied' => $item->sum('occupied'),
                    'available' => $item->sum('available'),
                    'out_of_order' => $item->sum('out_of_order'),
                    'inoperative' => $item->sum('inoperative'),
                    'planned' => $item->sum('planned'),
                    'unknown' => $item->sum('unknown'),
                    'blocked' => $item->sum('blocked'),
                ];
            });
        });
    }

    public function scopeFavorited($query, $user = null)
    {
        return $query->whereHas('subscribers', function ($query) use($user) {
            $query->where('user_id', $user ? $user->id : auth()->id());
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $search = explode(' ', $search);
            $query->where(function ($query) use ($search) {
                foreach ($search as $searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                }
            });
        });

        $query->when($filters['favoriteBy'] ?? null, function ($query, $user) {
            $query->favorited($user);
        });

        $query->when($filters['kwhRange'] ?? null, function ($query, $kwhRange) {
            $query->whereHas('chargers', function ($query) use ($kwhRange) {
                $query->whereBetween('kwh', $kwhRange);
            });
        });
    }

    /**
     * Will probably revert to using id as primary key
     * This is just to avoid refactor...hopefully
     */
    public function getIdAttribute()
    {
         return $this->external_id;
    }

    public function getIsFavoriteAttribute()
    {
         return $this->subscribers()->where('user_id', auth()->id())->exists();
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
