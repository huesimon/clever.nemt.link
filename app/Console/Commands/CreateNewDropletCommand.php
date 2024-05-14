<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use Illuminate\Console\Command;
use GrahamCampbell\DigitalOcean\Facades\DigitalOcean;

class CreateNewDropletCommand extends Command
{
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

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentDroplets = DigitalOcean::droplet()->getAll();

        if (count($currentDroplets) >= 2) {
            $this->info('There are already too many droplets.');
            return 1;
        }

        $snapshots = DigitalOcean::snapshot()->getAll();
        $snapshot = $snapshots[0];
        DigitalOcean::droplet()->create(
            names: 'New-Cron-Droplet',
            region: Arr::random($snapshot->regions),
            size: 's-1vcpu-1gb',
            image: $snapshot->id,
            sshKeys: ['3f:98:f1:a8:23:d9:7d:b6:3f:1f:89:ef:35:2d:f5:0d'],
        );

        $this->info('Droplet created successfully.');
        return 0;
    }
}
