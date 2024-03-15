<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;

class Map extends Component
{
    public $locations;
    public $publicLocations;
    public $otherNetworkLocations;

    public function render()
    {
        return view('livewire.map');
    }

    public function placeholder()
    {
        return <<<'HTML'
        <div class="fixed top-0 left-0 w-screen h-screen flex items-center justify-center">
            <div class="w-20 h-20 rounded-full bg-blue-500 animate-pulse"></div>
            <span? class="text-4xl ml-4">Loading...</span>
        </div>
        HTML;
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
        $this->otherNetworkLocations =  Location::with('address')
            ->whereHas('address')
            ->without('chargers')
            ->isPublic()
            ->when(request()->has('planned'), function ($query) {
                return $query->isPlanned();
            })
            ->origin('Hubject')
            ->get();
    }
}
