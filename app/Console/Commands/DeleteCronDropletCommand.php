<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use GrahamCampbell\DigitalOcean\Facades\DigitalOcean;

class DeleteCronDropletCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'do:delete-cron-droplet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete the cron droplet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $droplets = DigitalOcean::droplet()->getAll();

        foreach ($droplets as $droplet) {
            if ($droplet->name === 'New-Cron-Droplet') {
                $this->info('Deleting the cron droplet.');
                Log::info('Deleting the cron droplet.');
                DigitalOcean::droplet()->remove($droplet->id);
            }
        }

        $this->info('The cron droplet has been deleted.');

        return Command::SUCCESS;
    }
}
