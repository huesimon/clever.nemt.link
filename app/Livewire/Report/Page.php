<?php

namespace App\Livewire\Report;

use App\Enums\Island;
use App\Livewire\Forms\Report\Filters;
use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Page extends Component
{
    public Filters $filters;
    public Island $selectedIsland = Island::All;



    public function mount()
    {
        $this->filters->init();
    }

    public function render()
    {
        return view('livewire.report.page');
    }
}
