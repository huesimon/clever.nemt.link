<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
            'availableChargers as available_ccs' => function ($query) {
                $query->ccs();
            },
            'availableChargers as available_chademo' => function ($query) {
                $query->chademo();
            },
            'availableChargers as available_type2' => function ($query) {
                $query->type2();
            },
            'occupiedChargers',
            'occupiedChargers as occupied_ccs' => function ($query) {
                $query->ccs();
            },
            'occupiedChargers as occupied_chademo' => function ($query) {
                $query->chademo();
            },
            'occupiedChargers as occupied_type2' => function ($query) {
                $query->type2();
            },
            'outOfOrderChargers',
            'outOfOrderChargers as out_of_order_ccs' => function ($query) {
                $query->ccs();
            },
            'outOfOrderChargers as out_of_order_chademo' => function ($query) {
                $query->chademo();
            },
            'outOfOrderChargers as out_of_order_type2' => function ($query) {
                $query->type2();
            },
            'inoperativeChargers',
            'inoperativeChargers as inoperative_ccs' => function ($query) {
                $query->ccs();
            },
            'inoperativeChargers as inoperative_chademo' => function ($query) {
                $query->chademo();
            },
            'inoperativeChargers as inoperative_type2' => function ($query) {
                $query->type2();
            },
            'unknownChargers',
            'unknownChargers as unknown_ccs' => function ($query) {
                $query->ccs();
            },
            'unknownChargers as unknown_chademo' => function ($query) {
                $query->chademo();
            },
            'unknownChargers as unknown_type2' => function ($query) {
                $query->type2();
            },
            'plannedChargers',
            'plannedChargers as planned_ccs' => function ($query) {
                $query->ccs();
            },
            'plannedChargers as planned_chademo' => function ($query) {
                $query->chademo();
            },
            'plannedChargers as planned_type2' => function ($query) {
                $query->type2();
            },
        ])->chunk(500, function ($locations) use ($now) {
            foreach ($locations as $location) {
                $insert[] = [
                    'location_id' => $location->external_id,
                    'available' => $location->available_chargers_count,
                    'available_ccs' => $location->available_ccs,
                    'available_chademo' => $location->available_chademo,
                    'available_type2' => $location->available_type2,
                    'occupied' => $location->occupied_chargers_count,
                    'occupied_ccs' => $location->occupied_ccs,
                    'occupied_chademo' => $location->occupied_chademo,
                    'occupied_type2' => $location->occupied_type2,
                    'out_of_order' => $location->out_of_order_chargers_count,
                    'out_of_order_ccs' => $location->out_of_order_ccs,
                    'out_of_order_chademo' => $location->out_of_order_chademo,
                    'out_of_order_type2' => $location->out_of_order_type2,
                    'inoperative' => $location->inoperative_chargers_count,
                    'inoperative_ccs' => $location->inoperative_ccs,
                    'inoperative_chademo' => $location->inoperative_chademo,
                    'inoperative_type2' => $location->inoperative_type2,
                    'unknown' => $location->unknown_chargers_count,
                    'unknown_ccs' => $location->unknown_ccs,
                    'unknown_chademo' => $location->unknown_chademo,
                    'unknown_type2' => $location->unknown_type2,
                    'planned' => $location->planned_chargers_count,
                    'planned_ccs' => $location->planned_ccs,
                    'planned_chademo' => $location->planned_chademo,
                    'planned_type2' => $location->planned_type2,
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
