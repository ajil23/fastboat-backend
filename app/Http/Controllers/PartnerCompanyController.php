<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerCompanyController extends Controller
{
    // this function is for view all data from company table
    public function index(){
        return view('partner.company.index');
    }

    // this function is for view form to add company data
    public function add(){
        return view('partner.company.add');
    }
}
