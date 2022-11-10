<?php

namespace App\View\Components\Location;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $locationIds = Charger::distinct()->orderBy('updated_at', 'desc')->limit(15)->get(['location_id', 'updated_at']);
        $locations = Location::whereIn('id', $locationIds->pluck('location_id'))->get();
        // limit locations to 10
        return view('components.location.index', [
            'locations' => $locations,
        ]);
    }
}
