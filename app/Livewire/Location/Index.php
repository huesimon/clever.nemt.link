<?php

namespace App\Livewire\Location;

use App\Enums\ParkingTypes;
use App\Models\Charger;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    #[Url()]
    public $search;
    #[Url()]
    public $kwh; // slow, fast, hyper
    #[Url()]
    public $possibleOutOfOrder = false;
    #[Url()]
    public $parkingType;
    #[Url()]
    public $cleverOnly = false;
    public $user = null;

    public function render()
    {
        $query = Location::query();

        $query->when($this->possibleOutOfOrder, function ($query) {
            $query->whereHas('chargers', function ($query) {
                $query->mightBeOutOfOrder();
            });
        });

        $query->filter(['search' => $this->search]);

        $query->filter(['favoriteBy' => $this->user]);

        $query->filter(['kwhRange' => $this->getKwhRange($this->kwh)]);
        $query->filter(['parkingType' => $this->parkingType]);
        // $query->filter(['cleverOnly' => $this->cleverOnly]);
        $query->when($this->cleverOnly, function ($query) {
            $query->origin('Clever');
        });

        $query->withCount([
            'chargers as available_chargers_count' => function ($query) {
                $query->available();
            },
            'chargers as total_chargers_count' => function ($query) {
            },
        ]);

        $query->when(!$this->user, function ($query) {
            $query->orderByDesc(Charger::select('updated_at')
            ->whereColumn('location_external_id', 'locations.external_id')
            ->orderByDesc('updated_at')
            ->limit(1));
        });
        return view('livewire.location.index', [
            'locations' => $query->paginate(15),
        ]);
    }

    public function mount()
    {

    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKwh()
    {
        $this->resetPage();
    }

    public function getKwhRange($kwh)
    {
        switch ($kwh) {
            case 'slow':
                return [1, 24];
            case 'fast':
                return [25, 100];
            case 'hyper':
                return [101, 1000];
            default:
                return null;
        }
    }

    public function updateLocations()
    {
        Artisan::call('clever:chargers');
    }

    /**
     * TODO: Refactor if reverting to primary key
     */
    public function toggleFavorite(Location $location)
    {
        auth()->user()->toggleFavorite($location);
    }
}
