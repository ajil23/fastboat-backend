<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastboatShuttle extends Model
{
    use HasFactory;
    protected $table = "fastboatshuttle";
    protected $primaryKey = 's_id';
    protected $fillable = ['s_id', 's_trip', 's_area', 's_start', 's_end', 's_meeting_point','updated_by'];

    public function trip(){
        return $this->belongsTo(FastboatTrip::class,'s_trip','fbt_id');
    }
    public function area(){
        return $this->belongsTo(FastboatShuttleArea::class,'s_area','sa_id');
    }
    public function schedule()
    {
        return $this->belongsTo(FastboatSchedule::class);
    }
    public function departure(){
        return $this->belongsTo(MasterPort::class);
    }
    public function arrival(){
        return $this->belongsTo(MasterPort::class);
    }
    public function company(){
        return $this->belongsTo(DataCompany::class,'cpn_area','cpn_id');
    }
    public function island(){
        return $this->belongsTo(MasterIsland::class);
    }
}
