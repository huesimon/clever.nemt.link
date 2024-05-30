<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;

class Map extends Component
{
    public $locations;
    public $publicLocations;
    public $otherNetworkLocations;
    public $onlyDisplayPlanned = false;

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
            ->when($this->onlyDisplayPlanned, function ($query) {
                return $query->isPlanned();
            })
            ->get();
        $this->publicLocations = Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPublic()
            ->when($this->onlyDisplayPlanned, function ($query) {
                return $query->isPlanned();
            })
            ->origin('Clever')
            ->get();
        $this->otherNetworkLocations =  Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPublic()
            ->when($this->onlyDisplayPlanned, function ($query) {
                return $query->isPlanned();
            })
            ->origin('Hubject')
            ->get();
    }
}
