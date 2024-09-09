<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCurrency extends Model
{
    use HasFactory;
    protected $table = 'mastercurrency';
    protected $primaryKey = 'cy_id';
    protected $fillable = ['cy_id','cy_code', 'cy_name', 'cy_rate', 'cy_status'];
}
