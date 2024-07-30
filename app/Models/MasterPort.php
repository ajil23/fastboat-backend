<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPort extends Model
{
    use HasFactory;
    protected $table = 'masterport';
    protected $primaryKey = 'prt_id';
    protected $fillable = ['prt_id','prt_island','prt_name_en','prt_name_idn','prt_address','prt_code','prt_slug_en','prt_slug_idn','prt_keyword','prt_image1','prt_image2','prt_image3','prt_image4','prt_image5','prt_image6','prt_map','prt_description_en','prt_description_idn','prt_content_en','prt_content_idn'];

    public function island () {
        return $this->belongsTo(MasterIsland::class, 'prt_island', 'isd_id');
    }

}
