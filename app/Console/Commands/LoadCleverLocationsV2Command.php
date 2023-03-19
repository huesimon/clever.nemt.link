<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Company;
use App\Models\Location;
use Illuminate\Console\Command;
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
    protected $signature = 'clever:1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
                    'external_id' => $chunk->locationId,
                    'name' => $chunk->name,
                    'company_id' => '1',
                    'origin' => 'clever',
                    'is_roaming_allowed' => $chunk->publicAccess->isRoamingAllowed,
                    'is_public_visible' => $chunk->publicAccess->visibility === 'Always' ? true : false,
                    'coordinates' => $chunk->coordinates->lat . ',' . $chunk->coordinates->lng,
                ];
            })
            ->chunk(1000)
            ->each(function ($chunk) use ($cleverOperator, $bar) {
                Location::upsert(
                    $chunk->toArray(),
                    ['external_id'],
                    ['name', 'company_id']);
                // $bar->advance();
            });
        // $bar->finish();

        $cleverCollection->map(function ($chunk) {
            // $connectorMapResult[] = [
            //     'location_external_id' => $chunk->locationId,
            // ];
            collect($chunk->evses)->map(function ($evses) use (&$connectorMapResult, $chunk) {
                    collect($evses->connectors)->map(function ($connector) use (&$connectorMapResult, $chunk, $evses) {
                        // dd($evses);
                        $connectorMapResult[] = [
                            'location_external_id' => $chunk->locationId,
                            'balance' => $connector->balance,
                            'evse_id' => $evses->evseId,
                            'evse_connector_id' => $connector->evseConnectorId,
                            'connector_id' => $connector->connectorId,
                            'max_current_amp' => $connector->maxCurrentAmp ?? 0,
                            'max_power_kw' => $connector->maxPowerKw,
                            'plug_type' => $connector->plugType,
                            'speed' => $connector->speed,
                        ];
                    });
                });

                return $connectorMapResult;
            })
            ->chunk(100)
            ->each(function ($chunk) use ($cleverOperator, $bar) {
                collect($chunk)->each(function ($chunk) use ($cleverOperator, $bar) {
                    // dd($chunk);
                    Charger::upsert(
                        $chunk,
                        ['evse_id'],
                        ['location_external_id', 'balance', 'evse_connector_id', 'connector_id', 'max_current_amp', 'max_power_kw', 'plug_type', 'speed']);
                    $bar->advance();
                    // dd($chunk);
                });
            });

        $this->info("LoadCleanLocationsV2Command took " . (microtime(true) - $start) . " seconds");
        $this->info('Done!');
        return Command::SUCCESS;
    }
}
