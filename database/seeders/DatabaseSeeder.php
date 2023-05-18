<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Company;
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

        Company::factory()->create([
            'name' => 'Clever',
        ]);

        // $this->call(CompanySeeder::class);
        // $this->call(LocationSeeder::class);
        // $this->call(ChargerSeeder::class);
    }
}
