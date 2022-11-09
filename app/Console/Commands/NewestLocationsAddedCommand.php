<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Notifications\NewestLocationsNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewestLocationsAddedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locations:newest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will get the newest locations and send a message on telegram';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Getting newest locations...');
        // Find locations that have been created since the last cronjob ran
        $newestLocations = Location::where('created_at', '>=', now()->subHours(1))->take(10)->get();
        $totalNewestCount = Location::where('created_at', '>=', now()->subHours(1))->count();

        $this->info('Found ' . $newestLocations->count() . ' locations');
        $this->info('Found ' . $totalNewestCount . ' locations in total');

        // Send a message to telegram
        $this->info('Sending message to telegram...');
        Notification::send(['dont mind'], new NewestLocationsNotification());
        return Command::SUCCESS;
    }
}
