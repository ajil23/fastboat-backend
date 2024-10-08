<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastboatShuttleArea extends Model
{
    use HasFactory;
    protected $table = "fastboatshuttlearea";
    protected $primaryKey = 'sa_id';
    protected $fillable = ['sa_id', 'sa_island', 'sa_name', 'updated_by'];

    public function island(){
        return $this->belongsTo(MasterIsland::class,'sa_island','isd_id');
    }
}
