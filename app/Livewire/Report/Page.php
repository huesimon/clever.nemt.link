<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Page extends Component
{
    public Location $location;

    public function render()
    {
        return view('livewire.report.page');
    }
}
