<?php

namespace App\Livewire\Location;

use App\Models\Charger;
use Livewire\Component;
use App\Models\Location;
use App\Enums\ChargeSpeed;
use App\Enums\ParkingTypes;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;

class Index extends Component
{
    use WithPagination;
    #[Url()]
    public $search;
    #[Url(except: null)]
    public ?ChargeSpeed $kwh = null; // slow, fast, hyper
    #[Url()]
    public $possibleOutOfOrder = false;
    #[Url(except: null)]
    public ?ParkingTypes $parkingType = null;
    #[Url()]
    public $onlyClever = false;
    public $user = null;

    private function parkingFilter($query)
    {
        return $this->parkingType === null
            ? $query
            : $query->where('parking_type', $this->parkingType);
    }

    private function speedFilter($query)
    {
        return $this->kwh === null
            ? $query->with('chargers')
            : $query->with(['chargers' => function ($query) {
                $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
            }]);
    }

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
        $query = $this->parkingFilter($query);
        $query = $this->speedFilter($query);

        $query->when($this->onlyClever, function ($query) {
            $query->origin('Clever');
        });


        $query->withCount([
            'chargers as available_chargers_count' => function ($query) {
                $query->available();
                $query->when($this->kwh, function ($query) {
                    $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
                });
            },
            'chargers as total_chargers_count' => function ($query) {
                $query->when($this->kwh, function ($query) {
                    $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
                });
            },
        ]);

        $query->when(!$this->user, function ($query) {
            $query->orderByDesc(Charger::select('updated_at')
            ->whereColumn('location_external_id', 'locations.external_id')
            ->when($this->kwh, function ($query) {
                $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
            })
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

    public function selectParkingType($type)
    {

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
