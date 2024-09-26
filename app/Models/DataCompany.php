<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataCompany extends Model
{
    use HasFactory;
    protected $table = "datacompany";
    protected $primaryKey = 'cpn_id';
    protected $fillable = ['cpn_name', 'cpn_email', 'cpn_email_status', 'cpn_phone', 'cpn_whatsapp', 'cpn_address', 'cpn_website', 'cpn_status', 'cpn_type', 'cpn_logo'];

    public function schedules()
    {
        return $this->hasMany(FastboatSchedule::class);
    }
}
