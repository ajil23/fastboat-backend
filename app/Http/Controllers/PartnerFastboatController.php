<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerFastboatController extends Controller
{
    public function index(){
        return view('partner.fastboat.add');
    }
}
