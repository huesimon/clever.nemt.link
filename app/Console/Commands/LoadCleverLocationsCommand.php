<?php

namespace App\Console\Commands;

use App\Jobs\UpdateOrCreateCharger;
use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoadCleverLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clever:load';

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
        $start = microtime(true);
        $this->info('Loading locations from Clever endpoint...');
        $this->handleEndpoint();

        $this->info('Done!');
        Log::info('LoadCleverLocationsCommand took ' . (microtime(true) - $start) . ' seconds');
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


        $bar = $this->output->createProgressBar(sizeof($response->json()['clever']));
        foreach ($response->object()->clever as $uuid => $location) {
            $this->handleLocation($uuid, $location, $cleverOperator);
            $bar->advance();
        }
        $bar->finish();
    }

    private function handleLocation(string $uuid, Object $data, Company $company): void
    {
        $location = $company->locations()->updateOrCreate([
            'external_id' => $uuid,
        ], [
            'name' => $data->name,
            'origin' => $data->origin,
            'is_roaming_allowed' => $data->publicAccess->isRoamingAllowed,
            'is_public_visable' => $data->publicAccess->visibility,
            'coordinates' => $data->coordinates->lat . ', ' . $data->coordinates->lng,
        ]);

        $this->handleEvses($data->evses, $location);
    }

    private function handleEvses($evses, Location $location): void
    {
        foreach ($evses as $evse) {
            $connectors = collect($evse->connectors);
            foreach($connectors as $connector){
                $this->updateCharger($evse, $connector, $location);
            }
        }
    }

    private function updateCharger($evse, $connector, Location $location)
    {
        UpdateOrCreateCharger::dispatch($evse->evseId, $connector, $location->id);
    }
}
