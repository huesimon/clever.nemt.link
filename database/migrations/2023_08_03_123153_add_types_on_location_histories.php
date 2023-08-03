<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('location_histories', function (Blueprint $table) {
            $table->integer('available_ccs')->nullable()->after('available');
            $table->integer('available_chademo')->nullable()->after('available_ccs');
            $table->integer('available_type2')->nullable()->after('available_chademo');

            $table->integer('occupied_ccs')->nullable()->after('occupied');
            $table->integer('occupied_chademo')->nullable()->after('occupied_ccs');
            $table->integer('occupied_type2')->nullable()->after('occupied_chademo');

            $table->integer('unknown_ccs')->nullable()->after('unknown');
            $table->integer('unknown_chademo')->nullable()->after('unknown_ccs');
            $table->integer('unknown_type2')->nullable()->after('unknown_chademo');

            $table->integer('inoperative_ccs')->nullable()->after('inoperative');
            $table->integer('inoperative_chademo')->nullable()->after('inoperative_ccs');
            $table->integer('inoperative_type2')->nullable()->after('inoperative_chademo');

            $table->integer('out_of_order_ccs')->nullable()->after('out_of_order');
            $table->integer('out_of_order_chademo')->nullable()->after('out_of_order_ccs');
            $table->integer('out_of_order_type2')->nullable()->after('out_of_order_chademo');

            $table->integer('planned_ccs')->nullable()->after('planned');
            $table->integer('planned_chademo')->nullable()->after('planned_ccs');
            $table->integer('planned_type2')->nullable()->after('planned_chademo');

            $table->integer('blocked_ccs')->nullable()->after('blocked');
            $table->integer('blocked_chademo')->nullable()->after('blocked_ccs');
            $table->integer('blocked_type2')->nullable()->after('blocked_chademo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('location_histories', function (Blueprint $table) {
            $table->dropColumn('available_ccs');
            $table->dropColumn('available_chademo');
            $table->dropColumn('available_type2');
        });
    }
};
