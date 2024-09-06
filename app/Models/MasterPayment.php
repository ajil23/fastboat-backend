<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPayment extends Model
{
    use HasFactory;
    protected $table = 'masterpayment';
    protected $primaryKey = 'py_id';
    protected $fillable = ['py_id','py_name','py_value'];
}
