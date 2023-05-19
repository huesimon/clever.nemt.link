<?php

namespace App\Filament\Resources\ChargerResource\Pages;

use App\Filament\Resources\ChargerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChargers extends ListRecords
{
    protected static string $resource = ChargerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
