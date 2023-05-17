<?php

namespace App\Http\Livewire\Profile;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class LocationRadiusForm extends Component
{
    public $radii;
    public ?float $lat = null;
    public ?float $lng = null;
    public int $radius = 1000;
    public $name;

    public function save()
    {
        $this->validate([
            'lat' => ['required', 'numeric', 'min:-90', 'max:90'],
            'lng' => ['required', 'numeric', 'min:-180', 'max:180'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        auth()->user()->radius()->create([
            'lat' => $this->lat,
            'lng' => $this->lng,
            'name' => $this->name,
        ]);

        $this->reset(['lat', 'lng', 'name']);

        Session::flash('success', 'Location radius saved.');
    }


    public function render()
    {
        return view('livewire.profile.location-radius-form');
    }

    public function mount()
    {
        $this->radii = auth()->user()->radius;
    }
}
