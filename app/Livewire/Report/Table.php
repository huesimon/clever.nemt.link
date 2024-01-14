<?php

namespace App\Livewire\Report;

use App\Models\Location;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{

    use WithPagination;

    #[Url()]
    public $search;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query
                ->where('name', 'like', '%'.$this->search.'%');
    }

    public function render()
    {
        $query = Location::with('address');

        $this->applySearch($query);


        return view('livewire.report.table', [
            'locations' => $query->paginate(10),
        ]);
    }
}
