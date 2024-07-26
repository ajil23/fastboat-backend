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
        Schema::create('dataroute', function (Blueprint $table) {
            $table->id('rt_id');
            $table->string('rt_dept_island', 100);
            $table->string('rt_arrival_island', 100);
            $table->string('rt_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataroute');
    }
};