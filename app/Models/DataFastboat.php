<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataFastboat extends Model
{
    use HasFactory;
    protected $table = "datafastboat";
    protected $primaryKey = 'fb_id';
    protected $fillable = ['fb_id', 'fb_company', 'fb_name', 'fb_image1', 'fb_image2', 'fb_image3', 'fb_image4', 'fb_image5', 'fb_image6', 'fb_slug_en', 'fb_slug_ind', 'fb_keywords', 'fb_description_en', 'fb_description_ind', 'fb_content_en', 'fb_content_idn', 'fb_status', 'fb_term_condition_en', 'fb_term_condition_idn'];

    public function company(){
        return $this->belongsTo(DataCompany::class,'fb_company','cpn_id');
    }

}
