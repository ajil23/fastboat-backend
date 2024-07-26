<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataCompanyController extends Controller
{
    // this function is for view all data from company table
    public function index(){
        $company = DataCompany::paginate(10);
        $title = 'Delete Company Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('data.company.index', compact('company'));
    }

    // this function is for view form to add company data
    public function add(){
        return view('data.company.add');
    }

    // this function will request data from input in company add form
    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'cpn_name' => 'required',
            'cpn_email' => 'required',
            'cpn_phone' => 'required|numeric',
            'cpn_whatsapp' => 'required|numeric',
            'cpn_logo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'cpn_address' => 'required',
            'cpn_type' => 'required',
        ]);
        
        // Handle insert data to database
        $companyData = new DataCompany();
        $companyData -> cpn_name = $request->cpn_name;
        $companyData -> cpn_email = $request->cpn_email;
        $companyData -> cpn_phone = $request->cpn_phone;
        $companyData -> cpn_whatsapp = $request->cpn_whatsapp;
        $companyData -> cpn_address = $request->cpn_address;
        $companyData -> cpn_website = $request->cpn_website;
        $companyData -> cpn_type = $request->cpn_type;
        $companyData -> cpn_updated_by = Auth()->id();
        if ($request->hasFile('cpn_logo')) {
            $companyLogo = $request->file('cpn_logo')->store('cpn_logo');
            $companyData->cpn_logo = $companyLogo;
        }
        $companyData->save();
        toast('Your data as been submited!','success');
        return redirect()->route('company.view');
    }

    // this function will get the $id of the selected data and then view the company edit form
    public function edit($id){
        $companyEdit = DataCompany::find($id);
        $logoInfo = $companyEdit->cpn_logo;
        return view('data.company.edit', compact('companyEdit', 'logoInfo'));
    }

    // this function will get the $id of the selected data and request data from input in company edit from
    public function update(Request $request, $id){

         // Handle update data to database
         $companyData = DataCompany::find($id);
         $companyData -> cpn_name = $request->cpn_name;
         $companyData -> cpn_email = $request->cpn_email;
         $companyData -> cpn_phone = $request->cpn_phone;
         $companyData -> cpn_whatsapp = $request->cpn_whatsapp;
         $companyData -> cpn_address = $request->cpn_address;
         $companyData -> cpn_website = $request->cpn_website;
         $companyData -> cpn_type = $request->cpn_type;
         $companyData -> cpn_updated_by = Auth()->id();
         if ($request->hasFile('cpn_logo')) {
            Storage::delete($companyData->cpn_logo);
             $companyLogo = $request->file('cpn_logo')->store('cpn_logo');
             $companyData->cpn_logo = $companyLogo;
         }
         $companyData->save();
         toast('Your data as been edited!','success');
         return redirect()->route('company.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id){
        $companyDelete = DataCompany::find($id);
        $companyDelete->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('company.view');
    }

    // this function will get $id of selected data and view it in modal
    public function show($id){
        $companyData = DataCompany::find($id);
        return response()->json($companyData);
    }

    // this function will get $id of selected data and change the email status
    public function emailStatus($id){
        $companyData = DataCompany::find($id);

        if($companyData){
            if($companyData -> cpn_email_status){
                $companyData -> cpn_email_status = 0;
            } else{
                $companyData -> cpn_email_status = 1;
            }
            $companyData->save();
        }
        return back();
    }

    // this function will get $id of selected data and change the status
    public function companyStatus($id){
        $companyData = DataCompany::find($id);

        if($companyData){
            if($companyData -> cpn_status){
                $companyData -> cpn_status = 0;
            } else{
                $companyData -> cpn_status = 1;
            }
            $companyData->save();
        }
        return back();
    }
    

}