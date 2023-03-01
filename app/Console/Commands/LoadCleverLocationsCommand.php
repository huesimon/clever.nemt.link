<?php

namespace App\Console\Commands;

use App\Jobs\UpdateOrCreateCharger;
use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        // $url = route('ljson');
        $response = Http::get($url, [
            'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        ]);

        if ($response->failed()) {
            Log::error('Clever api failed to load');
            $this->error('Failed to load locations from Clever endpoint');
            return;
        }

        $this->info('Loaded locations from Clever endpoint');
        $cleverOperator = Company::firstOrCreate(['name' => 'Clever']);

        /**************************************************************************
         *  Load locations
         *************************************************************************/
        $insert = [];
        $bar = $this->output->createProgressBar(sizeof($response->json()['clever']));
        foreach ($response->object()->clever as $uuid => $location) {
            $this->handleLocation($uuid, $location, $cleverOperator, $insert);
            if (sizeof($insert) >= 300) {
                Location::upsert($insert, ['external_id', 'company_id'], ['name', 'origin', 'is_roaming_allowed', 'is_public_visible', 'coordinates']);
                $insert = [];
            }
            $bar->advance();
        }
        Location::upsert($insert, ['external_id', 'company_id'], ['name', 'origin', 'is_roaming_allowed', 'is_public_visible', 'coordinates']);
        $bar->finish();
        /**************************************************************************
         *  Load chargers
         *************************************************************************/
        $this->newLine(3);
        $this->info('Inserting chargers...');
        $bar = $this->output->createProgressBar(sizeof($response->json()['clever']));

        $insert = [];
        foreach ($response->object()->clever as $uuid => $location) {
            $this->handleEvses($uuid, $location->evses, $insert);
            if (sizeof($insert) >= 300) {
                // if the charger has these values already, skip
                Charger::upsert($insert, ['location_id', 'evse_id',], ['evse_connector_id', 'connector_id', 'balance', 'max_current_amp', 'max_power_kw', 'plug_type', 'power_type', 'speed']);
                $insert = [];
            }
            $bar->advance();
        }

        Charger::upsert($insert, ['location_id', 'evse_id',], ['evse_connector_id', 'connector_id', 'balance', 'max_current_amp', 'max_power_kw', 'plug_type', 'power_type', 'speed']);

        $this->newLine(3);
        $bar->finish();
    }

    private function handleLocation(string $uuid, Object $data, Company $company, array &$insert): void
    {
        $insert[] = [
            'company_id' => $company->id,
            'external_id' => $uuid,
            'name' => $data->name,
            'origin' => $data->origin,
            'is_roaming_allowed' => $data->publicAccess->isRoamingAllowed,
            'is_public_visible' => $data->publicAccess->visibility,
            'coordinates' => $data->coordinates->lat . ', ' . $data->coordinates->lng,
        ];
    }

    private function handleEvses($uuid, $evses, &$insert): void
    {
        foreach ($evses as $evse) {
            $connectors = collect($evse->connectors);
            foreach($connectors as $connector){
                Charger::where([
                    'location_id' => Location::where('external_id', $uuid)->first()->id,
                    'evse_id' => $evse->evseId,
                    'evse_connector_id' => $connector->evseConnectorId,
                    // 'balance' => $connector->balance,
                    // 'max_current_amp' => $connector->maxCurrentAmp,
                    // 'max_power_kw' => $connector->maxPowerKW,
                    // 'plug_type' => $connector->plugType,
                    // 'power_type' => $connector->powerType,
                    // 'speed' => $connector->speed,
                ])->exists() ?: $insert[] = [
                    'location_id' => Location::where('external_id', $uuid)->first()->id,
                    'evse_connector_id' => $connector->evseConnectorId,
                    'evse_id' => $evse->evseId,
                    'balance' => $connector->balance,
                    'connector_id' => $connector->connectorId,
                    'max_current_amp' => $connector?->maxCurrentAmp ?? null,
                    'max_power_kw' => $connector->maxPowerKw,
                    'plug_type' => $connector->plugType,
                    'power_type' => $connector?->powerType ?? null,
                    'speed' => $connector->speed,
                ];
            }
        }
    }
}
