<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class LoadCleverLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:clever';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load locations from Clever endpoint';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Loading locations from Clever endpoint...');
        $this->handleEndpoint();

        $this->info('Done!');
        return Command::SUCCESS;
    }

    private function handleEndpoint(): void
    {
        $url = 'https://clever-app-prod.firebaseio.com/prod/locations/V1.json';

        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to load locations from Clever endpoint');
            return;
        }

        $this->info('Loaded locations from Clever endpoint');
        $cleverOperator = Company::firstOrCreate(['name' => 'Clever']);
        foreach ($response->object()->clever as $uuid => $location) {
            $this->handleLocation($uuid, $location, $cleverOperator);
        }

    }

    private function handleLocation(string $uuid, Object $data, Company $company): void
    {
        $location = $company->locations()->firstOrCreate([
            'external_id' => $uuid,
        ], [
            'name' => $data->name,
            'origin' => $data->origin,
            'is_roaming_allowed' => $data->publicAccess->isRoamingAllowed,
            'is_public_visable' => $data->publicAccess->visibility,
            'coordinates' => $data->coordinates->lat . ', ' . $data->coordinates->lng,
        ]);

        if (! $location->wasRecentlyCreated) {
            $this->handleEvses($data->evses);
        }
    }

    private function handleEvses($evses): void
    {
        foreach ($evses as $evse) {
            $connectors = collect($evse->connectors);
            $isComboCharger = $connectors->count() > 1;
            if($isComboCharger){
                foreach($connectors as $connector){
                    $this->updateCharger($evse, $connector);
                }
            } else {
                // We can only get the charger status of a public charger, all private / InProximity will not be found
                $this->updateCharger($evse, $connectors->first());
            }
        }
    }

    private function updateCharger($evse, $connector)
    {
        try {
            Charger::where('evse_id', $evse->evseId)->firstOrFail()->update([
                'balance' => $connector->balance,
                'connector_id' => $connector->connectorId,
                'max_current_amp' => $connector->maxCurrentAmp,
                'max_power_kw' => $connector->maxPowerKw,
                'plug_type' => $connector->plugType,
                'power_type' => $connector->powerType,
                'speed' => $connector->speed,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

    }
}
