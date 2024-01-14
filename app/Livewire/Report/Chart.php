<?php

namespace App\Livewire\Report;

use App\Livewire\Forms\Report\Filters;
use Livewire\Component;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class Chart extends Component
{
    public $dataset = [];

    public Filters $filters;

    public function fillDataset()
    {
        $results = Location::select([
            DB::raw('DATE(created_at) as increment'),
            DB::raw('COUNT(*) as total'),
        ])
            ->tap(function ($query) {
                $this->filters->apply($query);
            })
            ->groupBy('increment')
            ->orderBy('increment')
            ->get();




        $this->dataset['labels'] = $results->pluck('increment');
        $this->dataset['values'] = $results->pluck('total');
    }

    public function mount()
    {
        $this->fillDataset();
    }

    public function render()
    {
        return view('livewire.report.chart', [
            'dataset' => $this->dataset,
        ]);
    }
}
