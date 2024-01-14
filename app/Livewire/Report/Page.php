<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\Location;
use Livewire\WithPagination;

class Page extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.report.page', [
            'locations' => Location::with('address')->paginate(10),
        ]);
    }
}
