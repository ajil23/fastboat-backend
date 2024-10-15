<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingData extends Model
{
    use HasFactory;
    protected $table = 'fastboatorder';
    protected $primaryKey = 'fbo_id';
    protected $fillable = ['fbo_order_id', 'fbo_booking_id', 'fbo_availability_id', 'fbo_trip_id', 'fbo_transaction_status', 'fbo_currency', 'fbo_payment_method', 'fbo_payment_status', 'fbo_trip_date', 'fbo_adult_nett', 'fbo_child_nett', 'fbo_total_nett', 'fbo_adult_publish', 'fbo_child_publish', 'fbo_total_publish', 'fbo_adult_currency', 'fbo_child_currency', 'fbo_total_currency', 'fbo_kurs', 'fbo_discount', 'fbo_price_cut', 'fbo_discount_total', 'fbo_refund', 'fbo_end_total', 'fbo_end_total_currency', 'fbo_profit', 'fbo_passenger', 'fbo_adult', 'fbo_child', 'fbo_infant', 'fbo_company', 'fbo_fast_boat', 'fbo_departure_island', 'fbo_departure_port', 'fbo_departure_time', 'fbo_arrival_island', 'fbo_arrival_port', 'fbo_arrival_time', 'fbo_checkin_point', 'fbo_mail_admin', 'fbo_mail_client'];

    public function order()
    {
        return $this->belongsTo(Contact::class, 'fbo_order_id', 'ctc_id');
    }
    public function availabiltity()
    {
        return $this->belongsTo(FastboatAvailability::class, 'fbo_availability_id', 'fba_id');
    }
    public function trip()
    {
        return $this->belongsTo(FastboatTrip::class, 'fbo_trip_id', 'fbt_id');
    }
    public function company()
    {
        return $this->belongsTo(DataCompany::class, 'fbo_company', 'cpn_id');
    }
    public function fastboat()
    {
        return $this->belongsTo(DataFastboat::class, 'fbo_fast_boat', 'fb_id');
    }
    public function deptIsland()
    {
        return $this->belongsTo(MasterIsland::class, 'fbo_departure_island', 'isd_id');
    }
    public function deptPort()
    {
        return $this->belongsTo(MasterPort::class, 'fbo_departure_port', 'prt_id');
    }
    public function arrivalIsland()
    {
        return $this->belongsTo(MasterIsland::class, 'fbo_arrival_island', 'isd_id');
    }
    public function arrivalPort()
    {
        return $this->belongsTo(DataFastboat::class, 'fbo_arrival_port', 'prt_id');
    }
    public function checkPoint()
    {
        return $this->belongsTo(FastboatCheckinPoint::class, 'fbo_checkin_point', 'fcp_id');
    }
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'fbo_order_id', 'ctc_id');
    }
}
