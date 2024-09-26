<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRoute extends Model
{
    use HasFactory;
    protected $table = "dataroute";
    protected $primaryKey = 'rt_id';
    protected $fillable = ['rt_id', 'rt_dept_island', 'rt_arrival_island'];

    public function trip()
    {
        return $this->hasMany(FastboatTrip::class);
    }

}
