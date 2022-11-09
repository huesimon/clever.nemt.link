<?php

namespace App\Jobs;

use App\Models\Charger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use stdClass;

class UpdateOrCreateCharger implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $evseId, public stdClass $connector, public $locationId)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Charger::updateOrCreate(['evse_connector_id' => $this->connector->evseConnectorId], [
            'location_id' => $this->locationId,
            'evse_id' => $this->evseId,
            'balance' => $this->connector->balance,
            'connector_id' => $this->connector->connectorId,
            'max_current_amp' => $this->connector?->maxCurrentAmp ?? null,
            'max_power_kw' => $this->connector->maxPowerKw,
            'plug_type' => $this->connector->plugType,
            'power_type' => $this->connector?->powerType ?? null,
            'speed' => $this->connector->speed,
        ]);
    }
}
