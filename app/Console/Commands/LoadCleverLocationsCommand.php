<?php

namespace App\Console\Commands;

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

    private function handleLocation(string $uuid, Object $location, Company $company): void
    {
        // find or create location based on external_id
        $location = $company->locations()->firstOrCreate([
            'external_id' => $uuid,
        ], [
            'name' => $location->name,
            'origin' => $location->origin,
            'is_roaming_allowed' => $location->publicAccess->isRoamingAllowed,
            'is_public_visable' => $location->publicAccess->visibility,
            'coordinates' => $location->coordinates->lat . ', ' . $location->coordinates->lng,
        ]);

        $this->info('Loaded location: ' . $location->name);
    }
}
