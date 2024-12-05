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
        Schema::create('fastboatlog', function (Blueprint $table) {
            $table->id('fbl_id');
            $table->foreign('fbl_booking_id')
                ->references('fbo_booking_id')
                ->on('fastboatorder')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('fbl_type');
            $table->string('fbl_data_before');
            $table->string('fbl_data_after');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastboatlog');
    }
};
