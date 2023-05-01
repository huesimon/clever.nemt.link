<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PDO;

class LoadCleverLocationsV2Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clever:locations';

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

        $url = 'https://clever-app-prod.firebaseio.com/prod/locations/V1.json';
        $response = Http::get($url, [
            'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        ]);

        if ($response->failed()) {
            Log::error('Clever api failed to load');
            $this->error('Failed to load locations from Clever endpoint');
            return;
        }

        $cleverOperator = Company::firstOrCreate(['name' => 'Clever']);
        $bar = $this->output->createProgressBar(sizeof($response->json()['clever']));
        $cleverCollection = collect($response->object()->clever);

        $cleverCollection->map(function ($chunk) {
                return [
                    'location' => [
                        'external_id' => $chunk->locationId,
                        'name' => $chunk->name,
                        'company_id' => '1',
                        'origin' => 'clever',
                        'is_roaming_allowed' => $chunk->publicAccess->isRoamingAllowed,
                        'is_public_visible' => $chunk->publicAccess->visibility,
                        'coordinates' => $chunk->coordinates->lat . ',' . $chunk->coordinates->lng,
                    ],
                    'address' => [
                        'addressable_id' => $chunk->locationId,
                        'addressable_type' => Location::class,
                        'address' => $chunk->address->address,
                        'city' => $chunk->address->city,
                        'country_code' => $chunk->address->countryCode,
                        'postal_code' => $chunk->address->postalCode,
                        // 'location' => $chunk->coordinates->lat . ',' . $chunk->coordinates->lng,
                        'lat' => $chunk->coordinates->lat,
                        'lng' => $chunk->coordinates->lng,
                    ]
                ];
            })
            ->chunk(1000)
            ->each(function ($chunk) use ($cleverOperator, $bar) {
                Location::upsert(
                    $chunk->pluck('location')->toArray(),
                    ['external_id'],
                    ['name', 'company_id']);

                Address::upsert(
                    $chunk->pluck('address')->toArray(),
                    ['addressable_id', 'addressable_type'],
                    ['address', 'city', 'country_code', 'postal_code']);
            });

        $chargersFromClever = [];

        $cleverCollection->each(function ($location) use (&$chargersFromClever) {
            collect($location->evses)->each(function ($evse) use ($location, &$chargersFromClever) {
                $evseId = $evse->evseId;
                collect($evse->connectors)->each(function ($connector) use ($location, &$chargersFromClever, $evseId) {
                    $chargersFromClever[] = [
                        'evse_id' => $evseId, // okay chargers endpoint uses evseId, not sure how that works when its not unique
                        'evse_connector_id' => $connector->evseConnectorId,
                        'connector_id' => $connector->connectorId,
                        'max_current_amp' => $connector->maxCurrentAmp ?? 0,
                        'max_power_kw' => $connector->maxPowerKw,
                        'plug_type' => $connector->plugType,
                        'speed' => $connector->speed,
                        'location_external_id' => $location->locationId,
                    ];
                });
            });
        });

        $chargersInDb = Charger::select([
            'evse_id',
            'evse_connector_id',
            'connector_id',
            'max_current_amp',
            'max_power_kw',
            'plug_type',
            'speed',
            'location_external_id',
        ])->get()->keyBy('evse_id');
        $chargersFromClever = collect($chargersFromClever);

        $chargersThatNeedsToBeCreated = collect();


        $chargersFromClever->each(function ($charger) use ($chargersInDb, &$chargersThatNeedsToBeCreated) {
            // $chargerInDb = $chargersInDb->firstWhere('evse_id', $charger['evse_id']);
            if (!$chargersInDb->has($charger['evse_id'])) {
                $chargersThatNeedsToBeCreated->push($charger);
            }
        });

        $chargersThatNeedsToBeCreated->chunk(5)->each(function ($chunk) {
            Charger::upsert($chunk->toArray(), ['evse_id']);
        });


        $this->info("LoadCleanLocationsV2Command took " . (microtime(true) - $start) . " seconds");
        $this->info('Done!');
        return Command::SUCCESS;
    }
}
