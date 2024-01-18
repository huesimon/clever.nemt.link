<?php

namespace App\Livewire\Location\Map;

use App\Models\Location;
use Livewire\Component;

class Leaflet extends Component
{
    public $locations = [];
    public $publicLocations = [];
    public $otherNetworkLocations = [];

    public function mount()
    {
        $this->locations = Location::with('address')
                ->whereHas('address')
                ->without('chargers')
                ->isPrivate()
                ->when(request()->has('planned'), function ($query) {
                    return $query->isPlanned();
                })
                ->take(1)
                ->get();
            $this->publicLocations = Location::with('address')
                ->whereHas('address')
                ->without('chargers')
                ->isPublic()
                ->when(request()->has('planned'), function ($query) {
                    return $query->isPlanned();
                })
                ->origin('Clever')
                ->take(1)
                ->get();
            $this->otherNetworkLocations = Location::with('address')
                ->whereHas('address')
                ->without('chargers')
                ->isPublic()
                ->when(request()->has('planned'), function ($query) {
                    return $query->isPlanned();
                })
                ->origin('Hubject')
                ->take(1)
                ->get();
    }

    public function render()
    {
        return view('livewire.location.map.leaflet');
    }
}
