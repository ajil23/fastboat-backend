<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitraFasboatController extends Controller
{
    public function index(){
        return view('mitra.fastboat.index');
    }
}
