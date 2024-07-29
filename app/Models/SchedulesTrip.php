<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulesTrip extends Model
{
    use HasFactory;
    protected $table = "schedulestrip";
    protected $primaryKey = 'fbt_id';
    protected $fillable = ['fbt_id', 'fbt_name', 'fbt_status', 'fbt_route', 'fbt_fastboat', 'fbt_schedule', 'fbt_dept_port', 'fbt_dept_time', 'fbt_time_limit', 'fbt_time_gap', 'fbt_arrival_port', 'fbt_arrival_time', 'fbt_info_en', 'fbt_info_idn', 'fbt_shuttle_type', 'fbt_shuttle_option'];

    public function route(){
        return $this->belongsTo(DataRoute::class,'fbt_route','rt_id');
    }
    public function fastboat(){
        return $this->belongsTo(DataFastboat::class,'fbt_fastboat','fb_id');
    }
    public function schedule(){
        return $this->belongsTo(SchedulesSchedule::class,'fbt_schedule','sch_id');
    }
    public function deptPort(){
        return $this->belongsTo(MasterPort::class,'fbt_dept_port','prt_id');
    }
    public function arrivalPort(){
        return $this->belongsTo(MasterPort::class,'fbt_arrival_port','prt_id');
    }
}
