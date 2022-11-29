<?php

namespace App\Http\Livewire\Location;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    public Collection $locations;

    public function render()
    {
        return view('livewire.location.index');
    }
}
