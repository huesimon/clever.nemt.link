<?php

namespace App\Console\Commands;

use App\Models\Address;
use App\Models\Charger;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class LoadCleverLocationsFirestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clever:firestore {perPage?} {page?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =  'Load Clever locations into Firestore';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $locationsThatAlreadyExists = Location::select('external_id', 'state', 'is_public_visible', 'updated_at')->getQuery()->get()->keyBy('external_id');
        $locationsToBeUpdated = new Collection();
        $locationsToBeCreated = new Collection();
        $addressesToBeCreated = new Collection();
        $chargersThatAlreadyExists = Charger::select('evse_id', 'max_power_kw', 'updated_at')->getQuery()->get()->keyBy('evse_id');
        $chargersToBeCreated = new Collection();
        $chargersToBeUpdated = new Collection();
        $perPage = $this->argument('perPage') ?? $this->ask('How many locations per page?', 100);
        $page = $this->argument('page');
        $this->info('Loading Clever locations into Firestore...');

        $url = 'https://firestore.googleapis.com/v1/projects/clever-app-prod/databases/(default)/documents/v4-locations?pageSize=' . $perPage . '&pageToken=' . $page;
        $response = Http::get($url);
        $this->info('Current URL: ' . $url);
        $locations = $response->json();
        $this->info('Locations loaded into Firestore');
        // dd($locations, $page, $perPage);
        $this->info('Total locations: ' . count($locations['documents']));
        $nextPage = array_key_exists('nextPageToken', $locations) ? $locations['nextPageToken'] : null;
        foreach ($locations['documents'] as $location) {
            $name = $location['fields']['name']['stringValue'];
            $locationId = $location['fields']['locationId']['stringValue'];
            $visibility = $location['fields']['publicAccess']['mapValue']['fields']['visibility']['stringValue'];
            $isRoamingAllowed = $location['fields']['publicAccess']['mapValue']['fields']['isRoamingAllowed']['booleanValue'];
            $lat = $location['fields']['coordinates']['mapValue']['fields']['lat']['doubleValue'];
            $lng = $location['fields']['coordinates']['mapValue']['fields']['lng']['doubleValue'];
            $origin = $location['fields']['origin']['stringValue'];
            $parkingType = $location['fields']['parkingType']['stringValue'] ?? null;
            $activeState = $location['fields']['state']['stringValue'];
            $partnerStatus = $location['fields']['partnerStatus']['stringValue'];
            $address = $location['fields']['address']['mapValue']['fields']['address']['stringValue'];
            $city = $location['fields']['address']['mapValue']['fields']['city']['stringValue'];
            $postalCode = $location['fields']['address']['mapValue']['fields']['postalCode']['stringValue'];
            $countryCode = $location['fields']['address']['mapValue']['fields']['countryCode']['stringValue'];
            // $evses = $location['fields']['evses']['mapValue']['fields'];
            $evses = data_get($location, 'fields.evses.mapValue.fields');
            if ($locationsThatAlreadyExists->has($locationId)) {
                if ($locationsThatAlreadyExists[$locationId]->state != $activeState) {
                    $locationsToBeUpdated->push([
                        'external_id' => $locationId,
                        'name' => $name,
                        'state' => $activeState,
                        'is_roaming_allowed' => $isRoamingAllowed,
                        'partner_status' => $partnerStatus,
                        'coordinates' => $lat . ',' . $lng,
                        'origin' => $origin,
                        'parking_type' => $parkingType,
                        'is_public_visible' => $visibility,
                        'is_roaming_partner' => $isRoamingAllowed,
                        'updated_at' => now(),
                    ]);
                }
            } else {
                $locationsToBeCreated->push([
                    'external_id' => $locationId,
                    'company_id' => 1,
                    'name' => $name,
                    'state' => $activeState,
                    'partner_status' => $partnerStatus,
                    'is_roaming_allowed' => $isRoamingAllowed,
                    'coordinates' => $lat . ',' . $lng,
                    'parking_type' => $parkingType,
                    'origin' => $origin,
                    'is_public_visible' => $visibility,
                    'is_roaming_partner' => $isRoamingAllowed,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $addressesToBeCreated->push([
                    'addressable_type' => Location::class,
                    'addressable_id' => $locationId,
                    'address' => $address,
                    'city' => $city,
                    'postal_code' => $postalCode,
                    'country_code' => $countryCode,
                    'lat' => $lat,
                    'lng' => $lng,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }

            // Create chargers
            foreach ($evses ?? [] as $key => $charger) {
                foreach ($charger['mapValue']['fields']['connectors']['mapValue']['fields'] as $connector) {
                    $balance = data_get($connector, 'mapValue.fields.balance.string', 'None');
                    $connectionType = data_get($connector, 'mapValue.fields.connectionType.stringValue', 'None');
                    $connectorId = data_get($connector, 'mapValue.fields.connectorId.integerValue', 'None');
                    $plugType = data_get($connector, 'mapValue.fields.plugType.stringValue', 'None');
                    $maxPowerKw = data_get($connector, 'mapValue.fields.maxPowerKw.doubleValue', 0);
                    $evseConnectorId = data_get($connector, 'mapValue.fields.evseConnectorId.stringValue', 'None');
                    $powerType = data_get($connector, 'mapValue.fields.powerType.stringValue', 'None');
                    $maxCurrentAmp = data_get($connector, 'mapValue.fields.maxCurrentAmp.integerValue', 0);

                    if ($chargersThatAlreadyExists->has($key)) {
                        if ($chargersThatAlreadyExists[$key]->max_power_kw != $maxPowerKw) {
                            $chargersToBeUpdated->push([
                                'evse_id' => $key,
                                'evse_connector_id' => $evseConnectorId,
                                'location_external_id' => $locationId,
                                'balance' => $balance,
                                // 'connection_type' => $connectionType,
                                'connector_id' => $connectorId,
                                'plug_type' => $plugType,
                                'max_power_kw' => $maxPowerKw,
                                'power_type' => $powerType,
                                'max_current_amp' => $maxCurrentAmp,
                                'updated_at' => now(),
                            ]);
                        }
                    } else {
                        $chargersToBeCreated->push([
                            'evse_id' => $key,
                            'evse_connector_id' => $evseConnectorId,
                            'location_external_id' => $locationId,
                            'balance' => $balance,
                            // 'connection_type' => $connectionType,
                            'connector_id' => $connectorId,
                            'plug_type' => $plugType,
                            'max_power_kw' => $maxPowerKw,
                            'power_type' => $powerType,
                            'max_current_amp' => $maxCurrentAmp,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                    break;
                }
            }

        }

        $this->info('Locations to be updated: ' . $locationsToBeUpdated->count());
        $this->info('Locations to be created: ' . $locationsToBeCreated->count());


        Location::insert($locationsToBeCreated->toArray());
        Location::upsert($locationsToBeCreated->toArray(), ['external_id'], ['name', 'state', 'partner_status', 'is_roaming_allowed', 'coordinates', 'parking_type', 'origin', 'is_public_visible', 'is_roaming_partner', 'updated_at']);
        Address::upsert($addressesToBeCreated->toArray(), ['addressable_id'], ['address', 'city', 'postal_code', 'country_code', 'lat', 'lng', 'updated_at']);

        $this->info('Chargers to be created: ' . $chargersToBeCreated->count());
        $this->info('Chargers to be updated: ' . $chargersToBeUpdated->count());

        Charger::insert($chargersToBeCreated->toArray());
        Charger::upsert($chargersToBeUpdated->toArray(), ['external_id'], ['location_external_id', 'evse_connector_id', 'balance', 'connector_id', 'plug_type', 'max_power_kw', 'power_type', 'max_current_amp', 'updated_at']);

        if ($nextPage) {
            $this->call('clever:firestore', ['perPage' => $perPage, 'page' => $nextPage]);
        }
    }
}
