<?php

namespace App\Observers;

use App\Models\LocationUser;
use App\Notifications\ChargingSpotsChangedNotification;

class LocationUserObserver
{
    /**
     * Handle the LocationUser "created" event.
     *
     * @param  \App\Models\LocationUser  $locationUser
     * @return void
     */
    public function created(LocationUser $locationUser)
    {
        //
    }

    /**
     * Handle the LocationUser "updated" event.
     *
     * @param  \App\Models\LocationUser  $locationUser
     * @return void
     */
    public function updated(LocationUser $locationUser)
    {
        $availablesSpotsBefore = $locationUser->getOriginal('last_available');
        $availablesSpotsNow = $locationUser->last_available;
        $locationUser->user->notify(new ChargingSpotsChangedNotification($locationUser->location, $availablesSpotsNow < $availablesSpotsBefore));
    }

    /**
     * Handle the LocationUser "deleted" event.
     *
     * @param  \App\Models\LocationUser  $locationUser
     * @return void
     */
    public function deleted(LocationUser $locationUser)
    {
        //
    }

    /**
     * Handle the LocationUser "restored" event.
     *
     * @param  \App\Models\LocationUser  $locationUser
     * @return void
     */
    public function restored(LocationUser $locationUser)
    {
        //
    }

    /**
     * Handle the LocationUser "force deleted" event.
     *
     * @param  \App\Models\LocationUser  $locationUser
     * @return void
     */
    public function forceDeleted(LocationUser $locationUser)
    {
        //
    }
}
