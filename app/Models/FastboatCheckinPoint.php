<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FastboatCheckinPoint extends Model
{
    use HasFactory;
    protected $table = "fastboatcheckinpoint";
    protected $primaryKey = 'fcp_id';
    protected $fillable = ['fcp_id', 'fcp_company', 'fcp_dept', 'fcp_address', 'fcp_maps'];

    public function company()
    {
        return $this->belongsTo(DataCompany::class, 'fcp_company', 'cpn_id');
    }

    public function departure()
    {
        return $this->belongsTo(MasterPort::class, 'fcp_dept', 'prt_id');
    }
}
