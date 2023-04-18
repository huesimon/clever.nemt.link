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
        $now = now();
        $this->info('Saving location history...');
        Log::info('Total locations: ' . Location::count());


        Location::withCount([
            'availableChargers',
            'occupiedChargers',
            'outOfOrderChargers',
            'inoperativeChargers',
            'unknownChargers',
            'plannedChargers',
        ])->chunk(500, function ($locations) use ($now) {
            foreach ($locations as $location) {
                $insert[] = [
                    'location_id' => $location->external_id,
                    'occupied' => $location->occupied_chargers_count,
                    'available' => $location->available_chargers_count,
                    'out_of_order' => $location->out_of_order_chargers_count,
                    'inoperative' => $location->inoperative_chargers_count,
                    'unknown' => $location->unknown_chargers_count,
                    'planned' => $location->planned_chargers_count,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            $this->info('Saving location history to database...');
            DB::table('location_histories')->insert($insert);
        });

        $this->info('Location history saved.');
        return Command::SUCCESS;
    }
}
