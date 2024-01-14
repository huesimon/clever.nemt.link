<?php

namespace App\Livewire\Report;

use Livewire\Component;
use App\Models\Location;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Livewire\Forms\Report\Filters;

class Table extends Component
{

    use WithPagination;

    #[Url()]
    public $search;

    public Filters $filters;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query
                ->where('name', 'like', '%'.$this->search.'%');
    }

    public function render()
    {
        $query = Location::with('address');

        $query = $this->filters->apply($query);
        $query = $this->applySearch($query);

        return view('livewire.report.table', [
            'locations' => $query->paginate(10),
        ]);
    }
}
