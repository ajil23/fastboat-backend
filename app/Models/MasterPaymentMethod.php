<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPaymentMethod extends Model
{
    use HasFactory;
    protected $table = 'masterpaymentmethod';
    protected $primaryKey = 'py_id';
    protected $fillable = ['py_id','py_name','py_value'];
}
