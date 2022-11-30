<?php

namespace App\Http\Livewire\Location;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{

    public $search = '';
    public $user = null;

    public function render()
    {
        $query = Location::query();

        if (str($this->search)->length() > 2) {
            $query->where('name', 'like', '%' . $this->search . '%');
        } elseif (!$this->user) {
            $locationIds = Charger::distinct()->orderBy('updated_at', 'desc')->limit(15)->get(['location_id', 'updated_at']);
            $query->whereIn('id', $locationIds->pluck('location_id'));
        }

        if ($this->user) {
            $query->favorited($this->user);
        }

        return view('livewire.location.index', [
            'locations' => $query->get(),
        ]);
    }
}
