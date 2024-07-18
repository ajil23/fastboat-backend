<?php

namespace App\Http\Controllers;

use App\Models\PartnerCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PartnerCompanyController extends Controller
{
    // this function is for view all data from company table
    public function index(){
        $company = PartnerCompany::paginate(10);
        return view('partner.company.index', compact('company'));
    }

    // this function is for view form to add company data
    public function add(){
        return view('partner.company.add');
    }

    // this function will request data from input in company add form
    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'cpn_name' => 'required',
            'cpn_email' => 'required',
            'cpn_email_status' => 'required',
            'cpn_phone' => 'required|numeric',
            'cpn_whatsapp' => 'required|numeric',
            'cpn_logo' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'cpn_address' => 'required',
            'cpn_status' => 'required',
            'cpn_type' => 'required',
        ]);
        
        // Handle insert data to database
        $companyData = new PartnerCompany();
        $companyData -> cpn_name = $request->cpn_name;
        $companyData -> cpn_email = $request->cpn_email;
        $companyData -> cpn_email_status = $request->cpn_email_status;
        $companyData -> cpn_phone = $request->cpn_phone;
        $companyData -> cpn_whatsapp = $request->cpn_whatsapp;
        $companyData -> cpn_address = $request->cpn_address;
        $companyData -> cpn_website = $request->cpn_website;
        $companyData -> cpn_status = $request->cpn_status;
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
        $companyEdit = PartnerCompany::find($id);
        $logoInfo = $companyEdit->cpn_logo;
        return view('partner.company.edit', compact('companyEdit', 'logoInfo'));
    }

    // this function will get the $id of the selected data and request data from input in company edit from
    public function update(Request $request, $id){

         // Handle update data to database
         $companyData = PartnerCompany::find($id);
         $companyData -> cpn_name = $request->cpn_name;
         $companyData -> cpn_email = $request->cpn_email;
         $companyData -> cpn_email_status = $request->cpn_email_status;
         $companyData -> cpn_phone = $request->cpn_phone;
         $companyData -> cpn_whatsapp = $request->cpn_whatsapp;
         $companyData -> cpn_address = $request->cpn_address;
         $companyData -> cpn_website = $request->cpn_website;
         $companyData -> cpn_status = $request->cpn_status;
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
        $companyDelete = PartnerCompany::find($id);
        $companyDelete->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('company.view');
    }

    // this function will get $id of selected data and view it in modal
    public function show($id){
        $companyData = PartnerCompany::find($id);
        return response()->json($companyData);
    }
    
}
