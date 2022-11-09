<?php

namespace App\Observers;

use App\Models\Charger;
use Illuminate\Support\Facades\Log;

class ChargerObserver
{
    /**
     * Handle the Charger "created" event.
     *
     * @param  \App\Models\Charger  $charger
     * @return void
     */
    public function created(Charger $charger)
    {
        // Log::info('Charger created', [
        //     'id' => $charger->id,
        //     'location_id' => $charger->location_id,
        //     'evse_id' => $charger->evse_id,
        //     'status' => $charger->status,
        // ]);
    }

    /**
     * Handle the Charger "updated" event.
     *
     * @param  \App\Models\Charger  $charger
     * @return void
     */
    public function updated(Charger $charger)
    {
        // Log::info('Charger updated', [
        //     'id' => $charger->id,
        //     'location_id' => $charger->location_id,
        //     'evse_id' => $charger->evse_id,
        //     'status' => $charger->status,
        // ]);
    }

    /**
     * Handle the Charger "deleted" event.
     *
     * @param  \App\Models\Charger  $charger
     * @return void
     */
    public function deleted(Charger $charger)
    {
        //
    }

    /**
     * Handle the Charger "restored" event.
     *
     * @param  \App\Models\Charger  $charger
     * @return void
     */
    public function restored(Charger $charger)
    {
        //
    }

    /**
     * Handle the Charger "force deleted" event.
     *
     * @param  \App\Models\Charger  $charger
     * @return void
     */
    public function forceDeleted(Charger $charger)
    {
        //
    }
}
