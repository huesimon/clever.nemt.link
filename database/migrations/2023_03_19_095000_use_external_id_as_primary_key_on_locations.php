<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        //drop *_new tables
        Schema::dropIfExists('locations_new');
        Schema::dropIfExists('chargers_new');
        Schema::dropIfExists('location_user_new');


        //create an intermediate table to hold the new primary key
        Schema::create('locations_new', function (Blueprint $table) {
            // $table->id();
            $table->string('external_id')->primary();
            $table->foreignId('company_id')->constrained(); //operator
            $table->string('name');
            $table->string('origin');
            $table->boolean('is_roaming_allowed');
            $table->string('is_public_visible');
            // $table->point('coordinates');
            $table->string('coordinates');
            $table->timestamps();

            // $table->unique(['external_id', 'company_id']);
        });

        //copy the data from the old table to the new table, get external_id from locations.external_id
        DB::statement('INSERT INTO locations_new (external_id, company_id, name, origin, is_roaming_allowed, is_public_visible, coordinates, created_at, updated_at) SELECT external_id, company_id, name, origin, is_roaming_allowed, is_public_visible, coordinates, created_at, updated_at FROM locations');

        //create intermediate table to hold the new primary key for location_user
        Schema::create('location_user_new', function (Blueprint $table) {
            // ref external_id on locations_new
            $table->string('location_external_id')->foreignIdFor('locations_new', 'external_id');
            $table->foreignId('user_id')->constrained();

            $table->timestamps();

            $table->primary(['location_external_id', 'user_id']);
        });

        //copy the data from the old table to the new table, get location_external_id from locations_new
        DB::statement('INSERT INTO location_user_new (location_external_id, user_id, created_at, updated_at) SELECT l.external_id, user_id, lu.created_at, lu.updated_at FROM location_user lu JOIN locations l ON lu.location_id = l.id');

        // create intermediate table to hold the new primary key for chargers
        Schema::create('chargers_new', function (Blueprint $table) {
            // $table->foreignId('location_external_id')->constrained('locations_new', 'external_id');
            // $table->primary('evse_id');
            $table->string('evse_id')->primary();
            $table->string('location_external_id')->foreignIdFor('locations_new', 'external_id');
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

            // $table->unique(['location_external_id', 'name']);
        });

        //Delete the oldest charger if there are multiple chargers with the same evse_id
        DB::statement('DELETE FROM chargers WHERE id IN (SELECT id FROM (SELECT id, ROW_NUMBER() OVER (PARTITION BY evse_id ORDER BY id) AS rownum FROM chargers) t WHERE t.rownum > 1)');

        //copy the data from the old table to the new table, get location_external_id from locations_new
        DB::statement('INSERT INTO chargers_new (evse_id, location_external_id, evse_connector_id, status, balance, connector_id, max_current_amp, max_power_kw, plug_type, power_type, speed, created_at, updated_at) SELECT evse_id, l.external_id, evse_connector_id, status, balance, connector_id, max_current_amp, max_power_kw, plug_type, power_type, speed, l.created_at, l.updated_at FROM chargers c JOIN locations l ON c.location_id = l.id');


        /*


        */


        //add the foreign keys to * new







        // drop the old tables
        Schema::dropIfExists('location_user');
        Schema::dropIfExists('chargers');
        Schema::dropIfExists('locations');

        // //rename the new tables
        Schema::rename('locations_new', 'locations');
        Schema::rename('location_user_new', 'location_user');
        Schema::rename('chargers_new', 'chargers');

        // //add the foreign keys
        // Schema::table('location_user', function (Blueprint $table) {
        //     $table->foreign('location_external_id')->references('external_id')->on('locations');
        // });

        // Schema::table('chargers', function (Blueprint $table) {
        //     $table->foreign('location_external_id')->references('external_id')->on('locations');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
