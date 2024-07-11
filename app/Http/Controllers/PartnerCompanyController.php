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

    // this function will request data from input in company add form
    public function store(Request $request){

    }

    // this function will get the $id of the selected data and then view the company edit form
    public function edit($id){

    }

    // this function will get the $id of the selected data and request data from input in company edit from
    public function update(Request $request, $id){

    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id){

    }
}
