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
        Schema::table('location_histories', function (Blueprint $table) {
            $table->integer('unknown')->after('available')->default(0);
            $table->integer('inoperative')->after('unknown')->default(0);
            $table->integer('out_of_order')->after('inoperative')->default(0);
            $table->integer('planned')->after('out_of_order')->default(0);
            $table->integer('blocked')->after('planned')->default(0);
        });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_histories', function (Blueprint $table) {
            $table->dropColumn('unknown');
            $table->dropColumn('inoperative');
            $table->dropColumn('out_of_order');
            $table->dropColumn('planned');
            $table->dropColumn('blocked');
        });
    }
};
