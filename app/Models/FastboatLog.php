<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastboatLog extends Model
{
    use HasFactory;
    protected $table = "fastboatlog";
    protected $primaryKey = 'fbl_id';
    protected $fillable = ['fbl_booking_id', 'fbl_type', 'fbl_data_before', 'fbl_data_after'];
}
