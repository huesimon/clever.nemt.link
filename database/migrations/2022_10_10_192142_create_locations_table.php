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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->foreignId('company_id')->constrained(); //operator
            $table->string('name');
            $table->string('origin');
            $table->boolean('is_roaming_allowed');
            $table->string('is_public_visable');
            // $table->point('coordinates');
            $table->string('coordinates');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
