<?php

use App\Models\Company;
use App\Models\Location;
use App\Models\User;

test('User can have a location radius', function($lat, $lng){
    $user = User::factory()->create();

    $user->radius()->create([
        'radius' => 1000,
        'lat' => $lat,
        'lng' => $lng
    ]);

    $radius = $user->radius()->first();
    $this->assertSame(1000, $radius->radius);

    $location = Location::factory()->forCompany(['name' => 'test'])->hasAddress(['lat' => $lat, 'lng' => $lng])->create();

    $this->assertCount(1, $user->radius()->get());
    $this->assertCount(1, $user->locationsWithinRadii());


    $locationFaraway = Location::factory()->forCompany(['name' => 'test'])->hasAddress(['lat' => $lat + 1, 'lng' => $lng + 1])->create();

    $this->assertCount(1, $user->radius()->get());
    $this->assertCount(1, $user->locationsWithinRadii());

})->with([
    [-67.4173143, 156.3436153]
]);
