<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;

class Map extends Component
{
    public $locations;
    public $publicLocations;
    public $hubjectLocations;
    public $ocpiLocations;

    public function render()
    {
        return view('livewire.map');
    }

    public function placeholder()
    {
        return view('loading');
    }

    public function mount()
    {
        $this->locations = Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPrivate()
            ->when(request()->has('planned'), function ($query) {
                return $query->isPlanned();
            })
            ->get();
        $this->publicLocations = Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPublic()
            ->when(request()->has('planned'), function ($query) {
                return $query->isPlanned();
            })
            ->origin('Clever')
            ->get();
        $this->hubjectLocations =  Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPublic()
            ->when(request()->has('planned'), function ($query) {
                return $query->isPlanned();
            })
            ->origin('Hubject')
            ->get();

        $this->ocpiLocations =  Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPublic()
            ->when(request()->has('planned'), function ($query) {
                return $query->isPlanned();
            })
            ->origin('OCPI')
            ->get();
    }
}
