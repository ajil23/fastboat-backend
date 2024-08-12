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
        Schema::create('datafastboat', function (Blueprint $table) {
            $table->id('fb_id');
            $table->unsignedBigInteger('fb_company'); 
            $table->foreign('fb_company')->references('cpn_id')->on('datacompany')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fb_name', 50);
            $table->string('fb_image1', 255);
            $table->string('fb_image2', 255);
            $table->string('fb_image3', 255);
            $table->string('fb_image4', 255)->nullable();
            $table->string('fb_image5', 255)->nullable();
            $table->string('fb_image6', 255)->nullable();
            $table->string('fb_slug_en', 50);
            $table->string('fb_slug_idn', 50);
            $table->text('fb_keywords');
            $table->text('fb_description_en');
            $table->text('fb_description_idn');
            $table->longText('fb_content_en');
            $table->longText('fb_content_idn');
            $table->longText('fb_term_condition_en')->nullable();
            $table->longText('fb_term_condition_idn')->nullable();
            $table->integer('fb_status')->default(1);
            $table->string('fb_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datafastboat');
    }
};
