<?php

namespace Database\Seeders;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChargerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //create chargers for locations
        Location::all()->each(function ($location) {
            Charger::factory()->count(2)->create([
                'location_id' => $location->id,
            ]);
        });
    }
}
