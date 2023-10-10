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
        $url = 'https://clever-app-prod.firebaseio.com/prod/availability/V3.json';

        $response = Http::get($url, [
            'ac' => Company::firstWhere('name', 'Clever')->app_check_token
        ]);

        // $this->saveResponseToFile($response);

        if ($response->failed()) {
            $this->error('Failed to load chargers from Clever endpoint');
            Log::error('Chargers failed load');
            Log::error($response->body());

            return Command::FAILURE;
        }

        $this->info('Loaded chargers from Clever endpoint');

        $bar = $this->output->createProgressBar(sizeof($response->json()));

        $knownChargersWithStatus = Charger::all('evse_id', 'location_external_id', 'status')
            ->mapWithKeys(function ($item) {
                return [$item['evse_id'] => $item];
            })->toArray();

        $insert = [];
        collect($response->json())
            ->each(function ($location) use ($knownChargersWithStatus, &$insert) {
                foreach ($location['evses'] as $charger) {
                    $knownCharger = $knownChargersWithStatus[$charger['evseId']] ?? null;
                    if ($knownCharger) {
                        if ($knownCharger['status'] !== $charger['status']) {
                            $insert[] = [
                                'evse_id' => $charger['evseId'],
                                'location_external_id' => $charger['locationId'],
                                'status' => $charger['status'],
                            ];
                        }
                    }
                    Charger::upsert($insert, ['evse_id'], ['status', 'updated_at']);
                    $insert = [];
                }
                return $insert;
            });

        $bar->finish();

        //1.4 seconds with 20k iterations
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
