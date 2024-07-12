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
        Schema::create('partnercompany', function (Blueprint $table) {
            $table->id('cpn_id');
            $table->string('cpn_name');
            $table->string('cpn_email');
            $table->string('cpn_email_status');
            $table->string('cpn_phone');
            $table->string('cpn_whatsapp');
            $table->string('cpn_address');
            $table->string('cpn_logo');
            $table->string('cpn_website');
            $table->string('cpn_status');
            $table->string('cpn_type');
            $table->string('cpn_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partnercompany');
    }
};
