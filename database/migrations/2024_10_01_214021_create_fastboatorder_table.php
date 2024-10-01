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
        Schema::create('fastboatorder', function (Blueprint $table) {
            $table->id('fbo_id');
            $table->unsignedBigInteger('fbo_order_id');
            $table->foreign('fbo_order_id')->references('ctc_id')->on('contact')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fbo_transaction_id')->nullable();
            $table->string('fbo_booking_id');
            $table->unsignedBigInteger('fbo_availability_id');
            $table->foreign('fbo_availability_id')->references('fba_id')->on('fastboatavailability')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbo_trip_id');
            $table->foreign('fbo_trip_id')->references('fbt_id')->on('fastboattrip')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fbo_transaction_status');
            $table->string('fbo_currency');
            $table->string('fbo_payment_method');
            $table->string('fbo_payment_status');
            $table->date('fbo_trip_date');
            $table->string('fbo_adult_nett');
            $table->string('fbo_child_nett');
            $table->string('fbo_total_nett');
            $table->string('fbo_adult_publish');
            $table->string('fbo_child_publish');
            $table->string('fbo_total_publish');

            $table->string('fbo_adult_currency');
            $table->string('fbo_child_currency');
            $table->string('fbo_total_currency');
            $table->string('fbo_kurs');
            $table->string('fbo_discount');
            $table->string('fbo_price_cut');
            $table->string('fbo_discount_total');
            $table->string('fbo_refund');
            $table->string('fbo_end_total');
            $table->string('fbo_profit');
            $table->text('fbo_passenger');
            $table->string('fbo_adult');
            $table->string('fbo_child');
            $table->string('fbo_infant');
            $table->unsignedBigInteger('fbo_company');
            $table->foreign('fbo_company')->references('cpn_id')->on('datacompany')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbo_fast_boat');
            $table->foreign('fbo_fast_boat')->references('fb_id')->on('datafastboat')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbo_departure_island');
            $table->foreign('fbo_departure_island')->references('isd_id')->on('masterisland')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbo_departure_port');
            $table->foreign('fbo_departure_port')->references('prt_id')->on('masterport')->onDelete('cascade')->onUpdate('cascade');
            $table->time('fbo_departure_time');
            $table->unsignedBigInteger('fbo_arrival_island');
            $table->foreign('fbo_arrival_island')->references('isd_id')->on('masterisland')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('fbo_arrival_port');
            $table->foreign('fbo_arrival_port')->references('prt_id')->on('masterport')->onDelete('cascade')->onUpdate('cascade');
            $table->time('fbo_arrival_time');
            $table->unsignedBigInteger('fbo_checking_point');
            $table->foreign('fbo_checking_point')->references('fcp_id')->on('fastboatcheckingpoint')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fbo_pickup')->nullable();
            $table->string('fbo_dropoff')->nullable();
            $table->string('fbo_shuttle_pickup')->nullable();
            $table->string('fbo_shuttle_dropoff')->nullable();
            $table->string('fbo_contact_pickup')->nullable();
            $table->string('fbo_contact_dropoff')->nullable();
            $table->string('fbo_mail_admin');
            $table->string('fbo_mail_client');
            $table->string('fbo_log')->nullable();
            $table->string('fbo_sourch')->nullable();
            $table->string('fbo_updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastboatorder');
    }
};
