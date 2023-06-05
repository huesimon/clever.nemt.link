<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Company;
use App\Models\Feedback;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'simon',
            'email' => 'simon@nemt.link',
            'password' => bcrypt('password'),
        ]);

        $companyClever = Company::factory()->create([
            'name' => 'Clever',
        ]);


        Location::factory()->for($companyClever)->count(100)->hasChargers(4)->create();

        Feedback::factory()->count(3)->create();
        Feedback::factory()->count(3)->published()->responded()->create();

        // $this->call(CompanySeeder::class);
        // $this->call(LocationSeeder::class);
        // $this->call(ChargerSeeder::class);
    }
}
