<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chargers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->string('evse_id')->nullable();
            $table->string('evse_connector_id')->nullable();
            $table->string('status')->nullable();
            $table->string('balance')->nullable();
            $table->string('connector_id')->nullable();
            $table->integer('max_current_amp')->nullable();
            $table->double('max_power_kw')->nullable();
            $table->string('plug_type')->nullable();
            $table->string('power_type')->nullable();
            $table->string('speed')->nullable();
            $table->timestamps();

            $table->unique(['evse_id', 'location_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chargers');
    }
};
