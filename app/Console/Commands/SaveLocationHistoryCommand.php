<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaveLocationHistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used to save the history of the location, such as the number of available and occupied chargers.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Saving location history...');
        Log::info('Total locations: ' . Location::count());

        DB::table('locations')->where('external_id', '0005b2ef-5dbf-ea11-a812-000d3ad97943')->orderBy('created_at')->each(function ($location) use (&$insert) {
            $insert[] = [
                'location_id' => $location->external_id,
                'occupied' => Charger::where('location_external_id', $location->external_id)->occupied()->count(),
                'available' => Charger::where('location_external_id', $location->external_id)->available()->count(),
                'out_of_order' => Charger::where('location_external_id', $location->external_id)->outOfOrder()->count(),
                'inoperative' => Charger::where('location_external_id', $location->external_id)->inoperative()->count(),
                'unknown' => Charger::where('location_external_id', $location->external_id)->unknown()->count(),
                'planned' => Charger::where('location_external_id', $location->external_id)->planned()->count(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        $this->info('Saving location history to database...');
        DB::table('location_histories')->insert($insert);

        $this->info('Location history saved.');
        return Command::SUCCESS;
    }
}
