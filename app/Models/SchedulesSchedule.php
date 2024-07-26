<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchedulesSchedule extends Model
{
    use HasFactory;
    protected $table = "schedulesschedule";
    protected $primaryKey = 'sch_id';
    protected $fillable = ['sch_id', 'fb_company', 'sch_name'];

    public function company(){
        return $this->belongsTo(DataCompany::class,'sch_company','cpn_id');
    }
}
