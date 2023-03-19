<?php

namespace App\Console\Commands;

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
                $bar->advance();
            });
        $bar->finish();

        $this->info("LoadCleanLocationsV2Command took " . (microtime(true) - $start) . " seconds");
        $this->info('Done!');
        return Command::SUCCESS;
    }
}
