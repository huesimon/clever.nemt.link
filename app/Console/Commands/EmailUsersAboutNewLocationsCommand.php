<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewLocationsNotification;
use Illuminate\Console\Command;

class EmailUsersAboutNewLocationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:email-users-about-new-locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will handle emailing users about new locations.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $createdAfter = now()->subDay();
        $users = User::notifyAboutNewLocations()->get();

        $users->each(function ($user) use ($createdAfter) {
            $locations = $user->locationsWithinRadii($createdAfter);
            if ($locations->count() > 0) {
                $this->info("Emailing {$user->email} about {$locations->count()} new locations...");
                $user->notify(new NewLocationsNotification($locations));
            }
        });
    }
}
