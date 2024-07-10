<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MitraCompanyController extends Controller
{
    public function index(){
        return view('mitra.company.index');
    }
}
