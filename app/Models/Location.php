<?php

namespace App\Models;

use App\Traits\HasAddress;
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

    use HasAddress;
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Is ->sum the correct function to use?
     */
    public function historyTimestamped($from = null, $to = null)
    {
        $from = $from ?? now()->subDays(3);
        $to = $to ?? now();
        return $this->history()
        ->whereBetween('created_at', [$from, $to])
        ->get()
        ->groupBy(function ($item, $key) {
            return $item->created_at_eu->format('Y-m-d H:i');
        })->map(function ($item, $key) {
            return [
                'occupied' => $item->sum('occupied'),
                'occupied_ccs' => $item->sum('occupied_ccs'),
                'occupied_chademo' => $item->sum('occupied_chademo'),
                'occupied_type2' => $item->sum('occupied_type2'),
                'available' => $item->sum('available'),
                'available_ccs' => $item->sum('available_ccs'),
                'available_chademo' => $item->sum('available_chademo'),
                'available_type2' => $item->sum('available_type2'),
                'out_of_order' => $item->sum('out_of_order'),
                'out_of_order_ccs' => $item->sum('out_of_order_ccs'),
                'out_of_order_chademo' => $item->sum('out_of_order_chademo'),
                'out_of_order_type2' => $item->sum('out_of_order_type2'),
                'inoperative' => $item->sum('inoperative'),
                'inoperative_ccs' => $item->sum('inoperative_ccs'),
                'inoperative_chademo' => $item->sum('inoperative_chademo'),
                'inoperative_type2' => $item->sum('inoperative_type2'),
                'planned' => $item->sum('planned'),
                'planned_ccs' => $item->sum('planned_ccs'),
                'planned_chademo' => $item->sum('planned_chademo'),
                'planned_type2' => $item->sum('planned_type2'),
                'unknown' => $item->sum('unknown'),
                'unknown_ccs' => $item->sum('unknown_ccs'),
                'unknown_chademo' => $item->sum('unknown_chademo'),
                'unknown_type2' => $item->sum('unknown_type2'),
                'blocked' => $item->sum('blocked'),
                'blocked_ccs' => $item->sum('blocked_ccs'),
                'blocked_chademo' => $item->sum('blocked_chademo'),
                'blocked_type2' => $item->sum('blocked_type2'),
            ];
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
            $search = array_filter($search, function ($value) {
                return !empty($value);
            });

            $query->where(function ($query) use ($search) {
                foreach ($search as $searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('address', function ($query) use ($searchTerm) {
                        $query->where('address', 'like', '%' . $searchTerm . '%')
                        ->orWhere('city', 'like', '%' . $searchTerm . '%')
                        ->orWhere('postal_code', 'like', '%' . $searchTerm . '%');
                    });
                }
            });
        });
        $query->when($filters['favoriteBy'] ?? null, function ($query, $user) {
            $query->favorited($user);
        });

        $query->when($filters['kwhRange'] ?? null, function ($query, $kwhRange) {
            $query->whereHas('chargers', function ($query) use ($kwhRange) {
                $query->whereBetween('max_power_kw', $kwhRange);
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

    public function scopeIsPublic($query)
    {
        return $query->where('is_public_visible', 'Always');
    }

    public function scopeIsPlanned($query)
    {
        return $query->where('state', 'planned');
    }

    public function scopeIsPrivate($query)
    {
        return $query->where('is_public_visible', 'InProximity');
    }

    public function scopeOrigin($query, $origin)
    {
        return $query->where('origin', $origin);
    }
}
