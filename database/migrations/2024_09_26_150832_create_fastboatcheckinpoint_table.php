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
        Schema::create('fastboatcheckinpoint', function (Blueprint $table) {
            $table->id('fcp_id');
            $table->unsignedBigInteger('fcp_company');
            $table->foreign('fcp_company')->references('cpn_id')->on('datacompany')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fcp_dept');
            $table->foreign('fcp_dept')->references('prt_id')->on('masterport')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fcp_address');
            $table->string('fcp_maps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastboatcheckingpoint');
    }
};
