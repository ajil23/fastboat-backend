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
        Schema::create('fastboattrip', function (Blueprint $table) {
            $table->id('fbt_id');
            $table->string('fbt_name', 255);
            $table->integer('fbt_status')->default(1);
            $table->unsignedBigInteger('fbt_route');
            $table->foreign('fbt_route')->references('rt_id')->on('dataroute')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbt_fastboat');
            $table->foreign('fbt_fastboat')->references('fb_id')->on('datafastboat')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbt_schedule');
            $table->foreign('fbt_schedule')->references('sch_id')->on('fastboatschedule')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbt_dept_port');
            $table->foreign('fbt_dept_port')->references('prt_id')->on('masterport')->onDelete('cascade')->onUpdate('cascade');
            $table->time('fbt_dept_time');
            $table->time('fbt_time_limit');
            $table->time('fbt_time_gap');
            $table->unsignedBigInteger('fbt_arrival_port');
            $table->foreign('fbt_arrival_port')->references('prt_id')->on('masterport')->onDelete('cascade')->onUpdate('cascade');
            $table->time('fbt_arrival_time');
            $table->text('fbt_info_en')->nullable();
            $table->text('fbt_info_idn')->nullable();
            $table->string('fbt_shuttle_type')->nullable();
            $table->string('fbt_shuttle_option')->nullable();
            $table->string('fbt_updated_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastboattrip');
    }
};
