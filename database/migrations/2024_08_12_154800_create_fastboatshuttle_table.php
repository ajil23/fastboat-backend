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
        Schema::create('fastboatshuttle', function (Blueprint $table) {
            $table->id('s_id');
            $table->unsignedBigInteger('s_trip');
            $table->foreign('s_trip')->references('fbt_id')->on('fastboattrip')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('s_area');
            $table->foreign('s_area')->references('sa_id')->on('fastboatshuttlearea')->onDelete('cascade')->onUpdate('cascade');
            $table->string('s_start', 10)->nullable();
            $table->string('s_end', 10)->nullable();
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
        Schema::dropIfExists('fastboatshuttle');
    }
};
