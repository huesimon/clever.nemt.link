<?php

namespace App\Livewire\Location;

use App\Models\User;
use App\Models\Charger;
use Livewire\Component;
use App\Models\Location;
use App\Enums\ChargeSpeed;
use App\Enums\ParkingTypes;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

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
    public $showInProximity = false;
    public ?User $user = null;
    #[Url()]
    public ?int $minAmountOfChargers = null;

    private function parkingFilter($query)
    {
        return $this->parkingType === null
            ? $query
            : $query->where('parking_type', $this->parkingType);
    }

    private function filterMinAmountOfChargers($query)
    {
        return $this->minAmountOfChargers === null
            ? $query
            : $query->whereHas('chargers', function ($query) {
                $query = $this->speedFilter($query);
                $query->groupBy('external_id')
                ->havingRaw('COUNT(*) >= ?', [$this->minAmountOfChargers])
                ->select('external_id');
            });
    }

    private function applySpeedFilter($query)
    {
        return $this->kwh === null
            ? $query->with('chargers')
            : $query->whereHas('chargers', function ($query) {
                $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
            })->with(['chargers' => function ($query) {
                $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
            }]);
    }

    private function speedFilter($query)
    {
        return $query->when($this->kwh, function ($query) {
            $query->whereBetween('max_power_kw', $this->kwh->kwhRange());
        });
    }

    private function filterInProximity($query)
    {
        return $this->showInProximity
            ? $query
            : $query->isPublic();
    }

    private function applyFilters($query)
    {
        $query->when($this->possibleOutOfOrder, function ($query) {
            $query->whereHas('chargers', function ($query) {
                $query->mightBeOutOfOrder();
            });
        });

        $query = $this->applySearch($query);


        $query->filter(['favoriteBy' => $this->user]);
        $query = $this->parkingFilter($query);

        $query = $this->applySpeedFilter($query);
        $query = $this->filterInProximity($query);
        $query = $this->filterMinAmountOfChargers($query);

        $query->when($this->onlyClever, function ($query) {
            $query->origin('Clever');
        });

        $query->withCount([
            'chargers as available_chargers_count' => function ($query) {
                $query->available();
                $query->when($this->kwh, function ($query) {
                    $this->speedFilter($query);
                });
            },
            'chargers as total_chargers_count' => function ($query) {
                $query->when($this->kwh, function ($query) {
                    $this->speedFilter($query);
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

        return $query;
    }

    private function applyAdvancedSearch($query)
    {
        $searchTerms = explode(' ', $this->search);

        foreach ($searchTerms as $key => $searchTerm) {
            if (Arr::get($searchTerms, $key+1) === null) {
                continue;
            }

            match ($searchTerm){
                'city:' => $query->orWhereHas('address', function ($query) use ($key, $searchTerm, $searchTerms) {
                    $query->where('city', 'like', '%' . $searchTerms[$key+1] . '%');
                }),
                'zip:' => $query->orWhereHas('address', function ($query) use ($key, $searchTerm, $searchTerms) {
                    $query->where('postal_code', 'like', '%' . $searchTerms[$key+1] . '%');
                }),
                'address:' => $query->orWhereHas('address', function ($query) use ($key, $searchTerm, $searchTerms) {
                    $query->where('address', 'like', '%' . $searchTerms[$key+1] . '%');
                }),
                default => null,
            };
        }

        return $query;
    }

    private function applySearch($query)
    {
        if (str($this->search)->contains(':')) {
            $query = $this->applyAdvancedSearch($query);
        } else {
            $query = $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhereHas('address', function ($query) {
                    $query->where('address', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('postal_code', 'like', '%' . $this->search . '%');
                });
        }

        return $query;
    }

    public function render()
    {
        $query = Location::query();

        $query = $this->applyFilters($query);

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
