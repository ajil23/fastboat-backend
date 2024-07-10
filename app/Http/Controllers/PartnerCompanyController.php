<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerCompanyController extends Controller
{
    public function index(){
        return view('partner.company.add');
    }
}
