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
        Schema::table('locations', function (Blueprint $table) {
                // $table->dropPrimary();
                $table->unsignedBigInteger('id')->after('external_id');
            }
        );

        // Update id col on location to increment, sorted by created_at
        DB::statement('SET @row_number = 0');
        DB::statement('UPDATE locations SET id = @row_number:=@row_number+1 ORDER BY created_at ASC');

        // Set id col on location to primary key
        Schema::table('locations', function (Blueprint $table) {
                $table->dropPrimary('external_id');
                // id should be primary and auto increment
                $table->primary('id');
            }
        );

        // Update location_external_id on chargers to match new location id
        Schema::table('chargers', function (Blueprint $table) {
                $table->dropPrimary('evse_id');
                // $table->dropForeign('chargers_location_external_id_foreign');
                // $table->dropColumn('location_external_id');
                $table->unsignedBigInteger('id')->after('evse_id');
                $table->unsignedBigInteger('location_id')->after('id');
            }
        );

        // Update location_external_id on chargers to match new location id
        DB::statement('SET @row_number = 0');
        DB::statement('UPDATE chargers SET id = @row_number:=@row_number+1 ORDER BY created_at ASC');

        //Insert location_id into chargers
        DB::statement('UPDATE chargers SET location_id = (SELECT id FROM locations WHERE locations.external_id = chargers.location_external_id)');


        Schema::table('chargers', function (Blueprint $table) {
            // $table->dropPrimary('external_id');
            // id should be primary and auto increment
            $table->primary('id');
            $table->foreign('location_id')->references('id')->on('locations');
        });


        // location_user
        Schema::table('location_user', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->after('user_id');
        });

        DB::statement('UPDATE location_user SET location_id = (SELECT id FROM locations WHERE locations.external_id = location_user.location_external_id)');

        Schema::table('location_user', function (Blueprint $table) {
            $table->dropPrimary(['location_external_id', 'user_id']);
            $table->dropColumn('location_external_id');
            // $table->primary(['location_id', 'user_id']);
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

};
