<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contact';
    protected $primarykey = 'ctc_id';
    protected $fillable = ['ctc_order_id', 'ctc_order_type', 'ctc_name', 'ctc_email', 'ctc_phone', 'ctc_nationality', 'ctc_note', 'ctc_booking_date', 'ctc_booking_time', 'ctc_ip_address', 'ctc_browser'];

    public function nationality()
    {
        return $this->belongsTo(MasterNationality::class, 'ctc_nationality', 'nas_id');
    }
}
