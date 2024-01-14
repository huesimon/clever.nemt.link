<?php

namespace App\Livewire\Forms\Report;

use Livewire\Form;
use App\Enums\Island;
use App\Models\Location;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;

class Filters extends Form
{

    #[Url()]
    public Island $island = Island::All;

    public string $search;


    public function islands()
    {
        return collect(Island::cases())->map(function ($island) {
            return [
                'value' => $island->value,
                'label' => $island->label(),
                'count' => Location::insidePolygon($island)->count(),
            ];
        })->toArray();
    }

    public function init()
    {
    }

    public function apply($query)
    {
        $query = $this->applyIsland($query);

        return $query;
    }



    public function applyIsland($query)
    {
        return $this->island === Island::All
            ? $query
            : $query->insidePolygon($this->island);
    }
}
