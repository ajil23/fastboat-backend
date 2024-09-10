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
        Schema::create('mastercurrency', function (Blueprint $table) {
            $table->id('cy_id');
            $table->string('cy_code', 3);
            $table->string('cy_name');
            $table->decimal('cy_rate', 10, 2)->unsigned()->default(0);
            $table->integer('cy_status')->default(1);
            $table->string('cy_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mastercurrency');
    }
};
