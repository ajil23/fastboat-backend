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
        Schema::create('contact', function (Blueprint $table) {
            $table->id('ctc_id');
            $table->string('ctc_order_id');
            $table->string('ctc_order_type');
            $table->string('ctc_name');
            $table->string('ctc_email');
            $table->string('ctc_phone');
            $table->unsignedBigInteger('ctc_nationality');
            $table->foreign('ctc_nationality')->references('nas_id')->on('masternationality')->onDelete('cascade')->onUpdate('cascade');
            $table->string('ctc_note')->nullable();
            $table->date('ctc_booking_date');
            $table->time('ctc_booking_time');
            $table->string('ctc_ip_address');
            $table->string('ctc_browser');
            $table->string('ctc_updated_by')->nullable();
            $table->string('ctc_created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact');
    }
};
