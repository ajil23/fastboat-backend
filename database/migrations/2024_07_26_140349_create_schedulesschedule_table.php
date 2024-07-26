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
        Schema::create('schedulesschedule', function (Blueprint $table) {
            $table->id('sch_id');
            $table->unsignedBigInteger('sch_company'); 
            $table->foreign('sch_company')->references('cpn_id')->on('datacompany')->onDelete('cascade')->onUpdate('cascade');
            $table->string('sch_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedulesschedule');
    }
};
