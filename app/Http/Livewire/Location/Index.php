<?php

namespace App\Http\Livewire\Location;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{

    public $search = '';

    public function render()
    {
        if (str($this->search)->length() > 2) {
            $locations = Location::where('name', 'like', '%' . $this->search . '%')->get();
        } else {
            $locationIds = Charger::distinct()->orderBy('updated_at', 'desc')->limit(15)->get(['location_id', 'updated_at']);
            $locations = Location::whereIn('id', $locationIds->pluck('location_id'))->get();
        }

        return view('livewire.location.index', [
            'locations' => $locations,
        ]);
    }
}
