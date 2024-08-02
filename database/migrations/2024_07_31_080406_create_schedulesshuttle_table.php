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
        Schema::create('schedulesshuttle', function (Blueprint $table) {
            $table->id('s_id');
            $table->unsignedBigInteger('s_trip');
            $table->foreign('s_trip')->references('fbt_id')->on('schedulestrip')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('s_area');
            $table->foreign('s_area')->references('sa_id')->on('schedulesshuttlearea')->onDelete('cascade')->onUpdate('cascade');
            $table->time('s_start')->nullable();
            $table->time('s_end')->nullable();
            $table->string('s_meeting_point', 100)->nullable();
            $table->string('s_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedulesshuttle');
    }
};
