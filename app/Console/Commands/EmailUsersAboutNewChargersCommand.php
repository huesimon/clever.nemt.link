<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Charger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewChargersNotification;

class EmailUsersAboutNewChargersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:email-users-about-new-chargers-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::notifyAboutNewLocations()->get();
        $users->each(function ($user) {
            $locationIds = $user->locationsWithinRadii(createdBefore: now()->subDay())->pluck('external_id');
            $newChargersBelongingToLocationsWithinRadii = Charger::with('location')->where('created_at', '>=', now()->subDay())
                ->whereIn('location_external_id', $locationIds)
                ->get();
            if ($newChargersBelongingToLocationsWithinRadii->count() > 0) {
                $this->info("Emailing {$user->email} about {$newChargersBelongingToLocationsWithinRadii->count()} new chargers...");
                Log::info("Emailing {$user->email} about {$newChargersBelongingToLocationsWithinRadii->count()} new chargers...");
                $user->notify(new NewChargersNotification($newChargersBelongingToLocationsWithinRadii));
            }
        });
    }
}
