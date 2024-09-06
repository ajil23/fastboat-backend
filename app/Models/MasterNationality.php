<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterNationality extends Model
{
    use HasFactory;
    protected $table = 'masternationality';
    protected $primaryKey = 'nas_id';
    protected $fillable = ['nas_id','nas_country','nas_country_code'];
}
