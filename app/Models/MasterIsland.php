<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterIsland extends Model
{
    use HasFactory;
    protected $table = 'masterisland';
    protected $primaryKey = 'isd_id';
    protected $fillable = ['isd_id','isd_name','isd_code','isd_slug_en','isd_slug_idn','isd_keyword','isd_image1','isd_image2','isd_image3','isd_image4','isd_image5','isd_image6','isd_map','isd_description_en','isd_description_idn','isd_content_en','isd_content_idn'];

}
