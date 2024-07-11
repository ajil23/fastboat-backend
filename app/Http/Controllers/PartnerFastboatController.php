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

    // this function will request data from input in fast boat add form
    public function store(Request $request){

    }
    
    // this function will get the $id of the selected data and then view the fast boat edit form
    public function edit($id){

    }

    // this function will get the $id of the selected data and request data from input in fast boat edit from
    public function update(Request $request, $id){

    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id){

    }
}
