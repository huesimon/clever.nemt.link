<?php

namespace App\Console\Commands;

use App\Models\Charger;
use App\Models\Company;
use Illuminate\Console\Command;
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
        $url = 'https://clever-app-prod.firebaseio.com/prod/availability/V3.json';

        $response = Http::get($url, [
            'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        ]);

        if ($response->failed()) {
            $this->error('Failed to load chargers from Clever endpoint');
            Log::error('Chargers failed load');
            Log::error($response->body());

            $this->call('do:create-new-droplet');

            return Command::FAILURE;
        }

        $this->info('Loaded chargers from Clever endpoint');

        $bar = $this->output->createProgressBar(sizeof($response->json()));

        $knownChargersWithStatus = Charger::select('evse_id', 'location_external_id', 'status', 'updated_at')
            ->getQuery()
            ->get()
            ->keyBy('evse_id');
        $chargersNeedsToBeCreated = [];
        $insert = [];

        foreach ($response->json() as $location) {
            foreach ($location['evses'] as $charger) {
                if ($knownChargersWithStatus->get($charger['evseId']) === null) {
                    $chargersNeedsToBeCreated[] = [
                        'evse_id' => $charger['evseId'],
                        'location_external_id' => $charger['locationId'],
                        'status' => $charger['status'],
                    ];
                } else {
                    // check if values have changed
                    if ($charger['status'] !== $knownChargersWithStatus->get($charger['evseId'])->status) {
                        $insert[] = [
                            'evse_id' => $charger['evseId'],
                            'location_external_id' => $charger['locationId'],
                            'status' => $charger['status'],
                        ];
                    }
                }
            }
        }

        collect($chargersNeedsToBeCreated)->chunk(1000)->each(function ($chunk) {
            Charger::upsert($chunk->toArray(), ['evse_id'], ['status', 'updated_at']);
        });

        collect($insert)->chunk(1000)->each(function ($chunk) {
            Charger::upsert($chunk->toArray(), ['evse_id'], ['status', 'updated_at']);
        });
        $bar->finish();
        $this->info("LoadCleverChargersV2Command completed in " . (microtime(true) - $start) . " seconds.");
        Log::info('LoadCleverChargersV2Command took ' . (microtime(true) - $start) . ' seconds');

        return Command::SUCCESS;
    }

    private function saveResponseToFile($response)
    {
        $filename = now()->format('Y-m-d-H-i') . '-clever-chargers';
        $file = fopen(storage_path('app/clever/' . $filename . '.json'), 'w');
        fwrite($file, json_encode($response->json()));
        fclose($file);
    }
}
