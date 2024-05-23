<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use GrahamCampbell\DigitalOcean\Facades\DigitalOcean;

class CreateNewDropletCommand extends Command
{
    public array $regions = [
        'ams2',
        'ams3',
        'blr1',
        'lon1',
        'nyc1',
        'nyc2',
        'nyc3',
        'sfo1',
        'sfo2',
        'sfo3',
        'sgp1',
        'syd1',
        'tor1',
    ];
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:create-new-droplet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new droplet';

    protected $maxAttempts = 5;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDroplets = DigitalOcean::droplet()->getAll();

        if (count($currentDroplets) >= 2) {
            $this->info('There are already too many droplets.');
            Log::info('There are already too many droplets.');
            return Command::FAILURE;
        }

        $snapshots = DigitalOcean::snapshot()->getAll();
        $snapshot = $snapshots[0];

        while ($this->maxAttempts > 0) {
            try {
                $this->createDroplet($snapshot);
                break;
            } catch (\Exception $e) {
                $this->error('Failed to create droplet. Trying again...');
                Log::error($e->getMessage());
                $this->maxAttempts--;
            }
        }

        $this->info('Droplet created successfully.');
        Log::info('Droplet created successfully.');

        return Command::SUCCESS;
    }

    private function createDroplet($snapshot): void
    {

        DigitalOcean::droplet()->create(
            names: 'New-Cron-Droplet',
            region: Arr::random($snapshot->regions),
            size: 's-1vcpu-1gb',
            image: $snapshot->id,
            sshKeys: ['3f:98:f1:a8:23:d9:7d:b6:3f:1f:89:ef:35:2d:f5:0d'],
        );
    }
}
