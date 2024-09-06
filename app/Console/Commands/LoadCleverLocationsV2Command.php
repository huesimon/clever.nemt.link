<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

        // clock()->event('clever:locations')->begin();
        $start = microtime(true);
        $this->info('Loading locations from Clever endpoint...');

        $url = 'https://clever-app-prod.firebaseio.com/prod/locations/V2/all.json';
        // $response = Http::get($url, [
        //     'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        // ]);

        // if ($response->failed()) {
        //     $this->error($response->body());
        //     Log::error('Clever api failed to load');
        //     $this->error('Failed to load locations from Clever endpoint');
        //     return;
        // }

        $cleverOperator = Company::firstOrCreate(['name' => 'Clever']);
        // $cleverCollection = collect($response->json());
        $cleverCollection = collect(Storage::json('public-locations.json'));
        // $cleverCollection = collect(Storage::json('clever-locations.json'));

        // dd($cleverCollection);

        $locationsThatAlreadyExists = Location::select('external_id', 'state', 'is_public_visible', 'updated_at')->getQuery()->get()->keyBy('external_id');
        $chargersThatAlreadyExists = Charger::select('evse_id', 'location_external_id', 'status', 'plug_type', 'updated_at')->getQuery()->get()->keyBy('evse_id');


        $newLocations = [];
        $newAddresses = [];
        $locationsThatNeedsToBeUpdated = [];
        $chargersThatNeedsToBeUpdated = [];

        foreach ($cleverCollection as $location) {
            $locationExternalId = $location['locationId'];

            if (!isset($location['evses'])) {
                $this->error('No evses found for location ' . $locationExternalId);
                continue;
            }
            foreach ($location['evses'] as $cleverCharger) {
                foreach ($cleverCharger['connectors'] as $connector) {
                    if ($chargersThatAlreadyExists->get($cleverCharger['evseId']) === null || $chargersThatAlreadyExists->get($cleverCharger['evseId'])->plug_type !== $connector['plugType']){
                        $chargersThatNeedsToBeUpdated[] = [
                            'evse_id' => $cleverCharger['evseId'],
                            'location_external_id' => $locationExternalId,
                            'balance' => $connector['balance'],
                            'connector_id' => $connector['connectorId'],
                            'evse_connector_id' => $connector['evseConnectorId'],
                            'max_power_Kw' => $connector['maxPowerKw'],
                            'plug_type' => $connector['plugType'],
                            'power_type' => isset($connector['powerType']) ? $connector['powerType'] : null,
                        ];
                    }
                }
            }

            if ($locationsThatAlreadyExists->get($locationExternalId) === null) {
                $newAddresses[] = [
                    'addressable_type' => Location::class,
                    'addressable_id' => $locationExternalId,
                    'address' => $location['address']['address'],
                    'city' => $location['address']['city'],
                    'country_code' => $location['address']['countryCode'],
                    'postal_code' => $location['address']['postalCode'],
                    'lat' => $location['coordinates']['lat'],
                    'lng' => $location['coordinates']['lng'],
                ];
                $newLocations[] = [
                    'external_id' => $locationExternalId,
                    'name' => $location['name'],
                    'origin' => $location['origin'],
                    'is_roaming_allowed' => $location['publicAccess']['isRoamingAllowed'],
                    'is_public_visible' => $location['publicAccess']['visibility'],
                    'coordinates' => $location['coordinates']['lat'] . ',' . $location['coordinates']['lng'],
                    'state' => $location['state'],
                    'company_id' => $cleverOperator->id,
                ];
            } else {
                $locationInDb = $locationsThatAlreadyExists->get($locationExternalId);
                if (
                    $locationInDb->state !== $location['state'] ||
                    $locationInDb->is_public_visible !== $location['publicAccess']['visibility']
                ) {
                    $locationsThatNeedsToBeUpdated[] = [
                        'external_id' => $locationExternalId,
                        'name' => $location['name'],
                        'company_id' => $cleverOperator->id,
                        'origin' => $location['origin'],
                        'state' => $location['state'],
                        'is_public_visible' => $location['publicAccess']['visibility'],
                        'is_roaming_allowed' => $location['publicAccess']['isRoamingAllowed'],
                        'coordinates' => $location['coordinates']['lat'] . ',' . $location['coordinates']['lng'],
                    ];
                }
            }
        }

        $this->info('Time before insert: ' . (microtime(true) - $start) . ' seconds');

        collect($newLocations)->chunk(1000)->each(function ($chunk) {
            Location::upsert($chunk->toArray(), ['external_id'], ['name', 'is_public_visible', 'is_roaming_allowed', 'state', 'company_id', 'coordinates']);
        });
        collect($newAddresses)->chunk(1000)->each(function ($chunk) {
            Address::upsert($chunk->toArray(), ['addressable_type', 'addressable_id'], ['address', 'city', 'country_code', 'postal_code', 'lat', 'lng']);
        });

        collect($locationsThatNeedsToBeUpdated)->chunk(1000)->each(function ($chunk) {
            Location::upsert($chunk->toArray(), ['external_id'], ['state', 'is_public_visible', 'updated_at']);
        });

        collect($chargersThatNeedsToBeUpdated)->chunk(1000)->each(function ($chunk) {
            Charger::upsert($chunk->toArray(), ['evse_id'], ['status', 'plug_type', 'power_type', 'balance', 'connector_id', 'max_power_Kw', 'updated_at']);
        });



        $this->info("LoadCleverLocationsV2Command took " . (microtime(true) - $start) . " seconds");
        $this->info('Done!');
        return Command::SUCCESS;
    }
}
