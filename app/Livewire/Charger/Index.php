<?php

namespace App\Livewire\Charger;

use App\Models\Charger;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $sortDesc = true;
    public $filterByStatus = 'all';

    public function render()
    {
        return view('livewire.charger.index',
            [
                'chargers' => Charger::whereHas('location', function ($query) {
                    return $query->where('is_public_visible', 'Always');
                })
                ->with(['location' => function ($query) {
                    return $query->where('is_public_visible', 'Always');
                }])
                ->when($this->filterByStatus != 'all', function ($query) {
                    return $query->where('status', $this->filterByStatus);
                })
                ->orderBy('updated_at', request()->get('sort', $this->sortDesc ? 'desc' : 'asc'))
                ->paginate(10),
                'statuses' => Charger::STATUSES,
                'chargersOutOfOrderCount' => Charger::where('status', Charger::OUT_OF_ORDER)->count(),
                'chargersAvailableCount' => Charger::where('status', Charger::AVAILABLE)->count(),
                'chargersOccupiedCount' => Charger::where('status', Charger::OCCUPIED)->count(),
                'totalChargersCount' => Charger::count(),
                'totalChargersCountLastWeek' => Charger::where('created_at', '>=', now()->subWeek())->count(),
                'longChargingSessionsCount' => Charger::where('status', Charger::OCCUPIED)->where('updated_at', '<=', now()->subHours(6))->where('updated_at', '>=', now()->subHours(12))->count(),
                'longerChargingSessionsCount' => Charger::where('status', Charger::OCCUPIED)->where('updated_at', '<=', now()->subHours(12))->count(),
            ]
        );
    }

    public function toggleDesc()
    {
        $this->sortDesc = !$this->sortDesc;
    }
}
