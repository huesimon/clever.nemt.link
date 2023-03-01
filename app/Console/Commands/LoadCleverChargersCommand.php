<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class LoadCleverChargersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clever:chargers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load chargers from Clever endpoint';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $locationsNotLoaded = [];
        $this->info('Loading chargers from Clever endpoint...');
        $this->handleEndpoint();

        // display the names of locations that were not loaded
        if (count($locationsNotLoaded) > 0) {
            $this->info('Locations not loaded:');
            foreach ($locationsNotLoaded as $location) {
                $this->info($location);
            }
        }

        $this->info('Done!');

        return Command::SUCCESS;
    }

    private function handleEndpoint(): void
    {
        $url = 'https://clever-app-prod.firebaseio.com/prod/availability/V1.json';
        // $url = route('ajson');

        $response = Http::get($url, [
            'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        ]);

        if ($response->failed()) {
            $this->error('Failed to load chargers from Clever endpoint');
            return;
        }
        // dd($response->object());

        $this->info('Loaded chargers from Clever endpoint');
        $bar = $this->output->createProgressBar(sizeof($response->json()['clever']));
        $insert = [];
        foreach ($response->object()->clever as $evseId => $charger) {
            $this->handleCharger($evseId, $charger, $insert);
            if (count($insert) >= 300) {
                // Remeber upsert wont trigger Model Observers
                Charger::upsert($insert, ['evse_id', 'location_id'], ['status']);
                $insert = [];
            }
            $bar->advance();
        }
        // Remeber upsert wont trigger Model Observers
        Charger::upsert($insert, ['evse_id', 'location_id'], ['status']);
        $bar->finish();
    }

    private function handleCharger(string $evseId, Object $charger, &$insert): void
    {
        $location = Location::where('external_id', $charger->locationId)->first();
        if (!$location) {
            $this->error('Location not found for charger ' . $evseId);
            $locationsNotLoaded[] = $charger->locationId;
            return;
        }
        // if charger has the same status, skip it
        $status = Charger::firstWhere(['evse_id' => $evseId, 'location_id' => $location->id])?->status;
        // dump("status: $status", "charger->status: $charger->status", "evseId: $evseId", "locationId: $location->id");
        if ($status && $status === $charger->status) {
            return;
        }

        // dump('updating charger ' . $evseId . ' with status ' . $charger->status);
        $insert[] = [
            'evse_id' => $evseId,
            'location_id' => $location->id,
            'status' => $charger->status,
        ];
    }
}
