<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\Url;

class Map extends Component
{
    public $locations;
    public $onlyDisplayPlanned = false;
    #[Url('origin')]
    public $origin = 'Clever';

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
            ->when($this->onlyDisplayPlanned, function ($query) {
                return $query->isPlanned();
            })
            ->origin($this->origin)
            ->get();
    }
}
