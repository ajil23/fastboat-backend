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
        Schema::create('destinyport', function (Blueprint $table) {
            $table->id('prt_id');
            $table->unsignedBigInteger('prt_island');
            $table->foreign('prt_island')->references('isd_id')->on('destinyisland')->onDelete('cascade')->onUpdate('cascade');
            $table->string('prt_name_en', 100);
            $table->string('prt_name_idn', 100);
            $table->string('prt_address', 100)->nullable();
            $table->string('prt_code', 100);
            $table->string('prt_slug_en', 100);
            $table->string('prt_slug_idn', 100);
            $table->text('prt_keyword')->nullable();
            $table->string('prt_image1', 255);
            $table->string('prt_image2', 255);
            $table->string('prt_image3', 255);
            $table->string('prt_image4', 255)->nullable();
            $table->string('prt_image5', 255)->nullable();
            $table->string('prt_image6', 255)->nullable();
            $table->text('prt_map');
            $table->text('prt_description_en');
            $table->text('prt_description_idn');
            $table->longText('prt_content_en');
            $table->longText('prt_content_idn');
            $table->string('prt_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinyport');
    }
};
