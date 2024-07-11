<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerCompany extends Model
{
    use HasFactory;
    protected $table = "partnercompany";
    protected $primaryKey = 'cpn_id';
    protected $fillable = ['cpn_name', 'cpn_email', 'cpn_email_status', 'cpn_phone', 'cpn_whatsapp', 'cpn_address', 'cpn_logo', 'cpn_website', 'cpn_status', 'cpn_type'];

    public function kategori(){
        return $this->belongsTo(Category::class,'kategori_id','id');
    }
}
