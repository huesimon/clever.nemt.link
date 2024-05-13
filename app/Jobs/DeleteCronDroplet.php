<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GrahamCampbell\DigitalOcean\Facades\DigitalOcean;

class DeleteCronDroplet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $droplets = DigitalOcean::droplet()->getAll();

        foreach ($droplets as $droplet) {
            if ($droplet->name === 'New Cron Droplet') {
                DigitalOcean::droplet()->remove($droplet->id);
            }
        }
    }
}
