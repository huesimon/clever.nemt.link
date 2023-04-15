<?php

namespace App\Http\Livewire\Location;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public $kwh; // slow, fast, hyper
    protected $queryString = ['search', 'kwh'];

    public $user = null;

    public function render()
    {
        $query = Location::query();

        if (str($this->search)->length() > 2) {
            $query->where('name', 'like', '%' . $this->search . '%');
        } elseif (!$this->user) {
            $locationIds = Charger::distinct()
                ->orderBy('updated_at', 'desc')
                // ->where('max_power_kw', '>=', 200)
                ->whereBetween('max_power_kw', $this->getKwhRange($this->kwh))
                ->limit(15)
                ->get(['location_external_id', 'updated_at']);
            $query->whereIn('external_id', $locationIds->pluck('location_external_id'));
        }

        if ($this->user) {
            $query->favorited($this->user);
        }

        $query->withCount([
            'chargers as available_chargers_count' => function ($query) {
                $query->available();
            },
            'chargers as total_chargers_count' => function ($query) {
            },
        ]);

        return view('livewire.location.index', [
            'locations' => $query->get(),
        ]);
    }

    public function getKwhRange($kwh)
    {
        switch ($kwh) {
            case 'slow':
                return [1, 24];
            case 'fast':
                return [25, 100];
            case 'hyper':
                return [101, 1000];
            default:
                return [1, 1000];
        }
    }

    public function updateLocations()
    {
        Artisan::call('clever:chargers');
    }

    /**
     * TODO: Refactor if reverting to primary key
     */
    public function toggleFavorite(Location $location)
    {
        auth()->user()->toggleFavorite($location);
    }
}
