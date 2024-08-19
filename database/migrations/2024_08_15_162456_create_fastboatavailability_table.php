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
        Schema::create('fastboatavailability', function (Blueprint $table) {
            $table->id('fba_id');
            $table->unsignedBigInteger('fba_trip_id');
            $table->foreign('fba_trip_id')->references('fbt_id')->on('schedulestrip')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fba_date');
            $table->time('fba_dept_time')->nullable();
            $table->time('fba_arriv_time')->nullable();
            $table->string('fba_adult_nett', 11)->nullable();
            $table->string('fba_child_nett', 11)->nullable();
            $table->string('fba_adult_publish', 11)->nullable();
            $table->string('fba_child_publish', 11)->nullable();
            $table->string('fba_discount', 11)->nullable();
            $table->string('fba_stock', 11)->nullable();
            $table->string('fba_status');
            $table->string('fba_shuttle_status');
            $table->text('fba_info');
            $table->string('fba_created_by');
            $table->string('fba_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastboatavailability');
    }
};
