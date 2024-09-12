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
        Schema::create('fastboatshuttlearea', function (Blueprint $table) {
            $table->id('sa_id');
            $table->unsignedBigInteger('sa_island');
            $table->foreign('sa_island')->references('isd_id')->on('masterisland')->onDelete('cascade')->onUpdate('cascade');
            $table->string('sa_name', 100);
            $table->string('sa_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastboatshuttlearea');
    }
};
