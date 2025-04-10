<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FillAddressDataOnLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:locations-address-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Since I forgot to copy the address when migrating servers, I need to fill the address data on the locations table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Filling address data on locations...');
        Location::doesntHave('address')
            ->each(function ($location) {
                $lat = Str::before($location->coordinates, ',');
                $lng = Str::after($location->coordinates, ',');
                $this->info('Creating address for location ' . $location->external_id);
                $this->info('Lat: ' . $lat);
                $this->info('Lng: ' . $lng);
                $location->address()->create([
                    'address' => 'not set',
                    'city' => 'not set',
                    'country_code' => 'not set',
                    'postal_code' => 'not set',
                    'lat' => $lat,
                    'lng' => $lng,
                ]);
            });
    }
}
