<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoadCleverChargersV2Command extends Command
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
        $start = microtime(true);

        $this->info('Loading chargers from Clever endpoint...');
        $url = 'https://clever-app-prod.firebaseio.com/prod/availability/V1.json';

        $response = Http::get($url, [
            'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        ]);

        if ($response->failed()) {
            $this->error('Failed to load chargers from Clever endpoint');
            return Command::FAILURE;
        }

        $this->info('Loaded chargers from Clever endpoint');

        $bar = $this->output->createProgressBar(sizeof($response->json()['clever']));

        $knownChargersWithStatus = Charger::all('evse_id', 'location_external_id', 'status')
        ->mapWithKeys(function ($item) {
            return [$item['evse_id'] => $item];
        })->toArray();

        $insert = [];
        collect($response->json()['clever'])
            ->map(function ($item) {
                return [
                    'evse_id' => $item['evseId'],
                    'status' => $item['status'],
                    'location_external_id' => $item['locationId'],
                ];
            })
            ->chunk(2000)
            ->each(function (Collection $chunk) use ($knownChargersWithStatus, &$insert, $bar) {
                $chunk->each(function ($item) use ($knownChargersWithStatus, &$insert, $bar) {
                    $knownCharger = $knownChargersWithStatus[$item['evse_id']] ?? null;

                    if ($knownCharger) {
                        if ($knownCharger['status'] !== $item['status']) {
                            $insert[] = $item;
                        }
                    }
                });

                Charger::upsert($insert, ['evse_id'], ['status', 'updated_at']);
                $insert = [];

                $bar->advance($chunk->count());
        });

        $bar->finish();

        //1.4 seconds with 20k iterations
        $this->info("LoadCleverChargersV2Command completed in " . (microtime(true) - $start) . " seconds.");
        Log::info('LoadCleverChargersV2Command took ' . (microtime(true) - $start) . ' seconds');

        return Command::SUCCESS;
    }
}
