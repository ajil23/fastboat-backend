<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerFastboatController extends Controller
{
    // this function is for view all data from fastboat table
    public function index(){
        return view('partner.fastboat.index');
    }

    // this function is for view form to add fastboat data
    public function add(){
        return view('partner.fastboat.add');
    }
}
