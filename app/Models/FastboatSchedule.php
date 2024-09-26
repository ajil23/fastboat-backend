<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastboatSchedule extends Model
{
    use HasFactory;
    protected $table = "fastboatschedule";
    protected $primaryKey = 'sch_id';
    protected $fillable = ['sch_id', 'fb_company', 'sch_name'];

    public function company(){
        return $this->belongsTo(DataCompany::class,'sch_company','cpn_id');
    }

    public function trip()
    {
        return $this->hasMany(FastboatTrip::class);
    }

    public function shuttle()
    {
        return $this->hasMany(FastboatSchedule::class);
    }
}
