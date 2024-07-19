<?php

namespace App\Http\Controllers;

use App\Models\PartnerCompany;
use App\Models\PartnerFastboat;
use Illuminate\Http\Request;

class PartnerFastboatController extends Controller
{
    // this function is for view all data from fastboat table
    public function index(){
        $fastboat = PartnerFastboat::paginate(10);
        return view('partner.fastboat.index', compact('fastboat'));
    }

    // this function is for view form to add fastboat data
    public function add(){
        $company = PartnerCompany::all();
        return view('partner.fastboat.add', compact('company'));
    }

    // this function will request data from input in fast boat add form
    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'fb_name' => 'required',
            'fb_company' => 'required',
            'fb_image1' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'fb_image2' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'fb_image3' => 'required|image|mimes:jpeg,jpg,png|max:5120',
            'fb_keywords' => 'required',
            'fb_slug_en' => 'required',
            'fb_slug_idn' => 'required',
            'fb_description_en' => 'required',
            'fb_description_idn' => 'required',
            'fb_content_en' => 'required',
            'fb_content_idn' => 'required',
        ]);

        // Handle insert data to database
        $fastboatData = new PartnerFastboat();
        $fastboatData -> fb_name = $request->fb_name;
        $fastboatData -> fb_company = $request->fb_company;
        $fastboatData -> fb_keywords = $request->fb_keywords;
        $fastboatData -> fb_slug_en = $request->fb_slug_en;
        $fastboatData -> fb_slug_idn = $request->fb_slug_idn;
        $fastboatData -> fb_description_en = $request->fb_description_en;
        $fastboatData -> fb_description_idn = $request->fb_description_idn;
        $fastboatData -> fb_content_en = $request->fb_content_en;
        $fastboatData -> fb_content_idn = $request->fb_content_idn;
        $fastboatData -> fb_updated_by = Auth()->id();

        // handle image store
        if ($request->hasFile('fb_image1')) {
            $fbimg1 = $request->file('fb_image1')->store('fb_image1');
            $fastboatData->fb_image1 = $fbimg1;
        }
        if ($request->hasFile('fb_image2')) {
            $fbimg2 = $request->file('fb_image2')->store('fb_image2');
            $fastboatData->fb_image2 = $fbimg2;
        }
        if ($request->hasFile('fb_image3')) {
            $fbimg3 = $request->file('fb_image3')->store('fb_image3');
            $fastboatData->fb_image3 = $fbimg3;
        }
        if ($request->hasFile('fb_image4')) {
            $fbimg4 = $request->file('fb_image4')->store('fb_image4');
            $fastboatData->fb_image4 = $fbimg4;
        }
        if ($request->hasFile('fb_image5')) {
            $fbimg5 = $request->file('fb_image5')->store('fb_image5');
            $fastboatData->fb_image5 = $fbimg5;
        }
        if ($request->hasFile('fb_image6')) {
            $fbimg6 = $request->file('fb_image6')->store('fb_image6');
            $fastboatData->fb_image6 = $fbimg6;
        }
        $fastboatData->save();
        toast('Your data as been submited!','success');
        return redirect()->route('fastboat.view');
    }
    
    // this function will get the $id of the selected data and then view the fast boat edit form
    public function edit($id){
        $company = PartnerCompany::all();
        $fastboatEdit = PartnerFastboat::find($id);
        return view('partner.fastboat.edit', compact('fastboatEdit', 'company'));
    }

    // this function will get the $id of the selected data and request data from input in fast boat edit from
    public function update(Request $request, $id){

        // Handle insert data to database
        $fastboatData = PartnerFastboat::find($id);
        $fastboatData -> fb_name = $request->fb_name;
        $fastboatData -> fb_company = $request->fb_company;
        $fastboatData -> fb_keywords = $request->fb_keywords;
        $fastboatData -> fb_slug_en = $request->fb_slug_en;
        $fastboatData -> fb_slug_idn = $request->fb_slug_idn;
        $fastboatData -> fb_description_en = $request->fb_description_en;
        $fastboatData -> fb_description_idn = $request->fb_description_idn;
        $fastboatData -> fb_content_en = $request->fb_content_en;
        $fastboatData -> fb_content_idn = $request->fb_content_idn;
        $fastboatData -> fb_updated_by = Auth()->id();

        // handle image store
        if ($request->hasFile('fb_image1')) {
            $fbimg1 = $request->file('fb_image1')->store('fb_image1');
            $fastboatData->fb_image1 = $fbimg1;
        }
        if ($request->hasFile('fb_image2')) {
            $fbimg2 = $request->file('fb_image2')->store('fb_image2');
            $fastboatData->fb_image2 = $fbimg2;
        }
        if ($request->hasFile('fb_image3')) {
            $fbimg3 = $request->file('fb_image3')->store('fb_image3');
            $fastboatData->fb_image3 = $fbimg3;
        }
        if ($request->hasFile('fb_image4')) {
            $fbimg4 = $request->file('fb_image4')->store('fb_image4');
            $fastboatData->fb_image4 = $fbimg4;
        }
        if ($request->hasFile('fb_image5')) {
            $fbimg5 = $request->file('fb_image5')->store('fb_image5');
            $fastboatData->fb_image5 = $fbimg5;
        }
        if ($request->hasFile('fb_image6')) {
            $fbimg6 = $request->file('fb_image6')->store('fb_image6');
            $fastboatData->fb_image6 = $fbimg6;
        }
        $fastboatData->update();
        toast('Your data as been edited!','success');
        return redirect()->route('fastboat.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id){
        $fastboatData = PartnerFastboat::find($id);
        $fastboatData -> delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('fastboat.view');
    }

    // this function will get $id of selected data and view it in modal
    public function show($id){
        $fastboatData = PartnerFastboat::find($id);
        return response()->json($fastboatData);
    }

    // this function will get $id of selected data and change the status
    public function status($id){
        $fastboatData = PartnerFastboat::find($id);

        if($fastboatData){
            if($fastboatData -> fb_status){
                $fastboatData -> fb_status = 0;
            } else{
                $fastboatData -> fb_status = 1;
            }
            $fastboatData->save();
        }
        return back();
    }
}
