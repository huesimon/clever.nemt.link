<?php

namespace App\Livewire\Forms\Report;

use Livewire\Form;
use App\Enums\Island;
use App\Models\Location;
use Livewire\Attributes\Validate;

class Filters extends Form
{
    public string $island;

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
        $this->island = 'Zealand';
    }
}
