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
        Schema::create('destinyisland', function (Blueprint $table) {
            $table->id('isd_id');
            $table->string('isd_name', 100);
            $table->string('isd_code', 100);
            $table->string('isd_slug_en', 100);
            $table->string('isd_slug_idn', 100);
            $table->text('isd_keyword')->nullable();
            $table->string('isd_image1', 255);
            $table->string('isd_image2', 255);
            $table->string('isd_image3', 255);
            $table->string('isd_image4', 255)->nullable();
            $table->string('isd_image5', 255)->nullable();
            $table->string('isd_image6', 255)->nullable();
            $table->text('isd_map');
            $table->text('isd_description_en');
            $table->text('isd_description_idn');
            $table->text('isd_content_en');
            $table->text('isd_content_idn');
            $table->string('isd_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinyisland');
    }
};
