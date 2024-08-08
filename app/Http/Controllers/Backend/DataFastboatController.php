<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\DataFastboat;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataFastboatController extends Controller
{
    // this function is for view all data from fastboat table
    public function index()
    {
        $fastboat = DataFastboat::all();
        $title = 'Delete Fastboat Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('data.fastboat.index', compact('fastboat'));
    }

    // this function is for view form to add fastboat data
    public function add()
    {
        $company = DataCompany::orderBy('cpn_name', 'asc')->having('cpn_type', 'fast_boat')->get();
        return view('data.fastboat.add', compact('company'));
    }

    // this function will request data from input in fast boat add form
    public function store(Request $request)
    {
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
        $fastboatData = new DataFastboat();
        $fastboatData->fb_name = $request->fb_name;
        $fastboatData->fb_company = $request->fb_company;
        $fastboatData->fb_keywords = $request->fb_keywords;
        $fastboatData->fb_slug_en = $request->fb_slug_en;
        $fastboatData->fb_slug_idn = $request->fb_slug_idn;
        $fastboatData->fb_description_en = $request->fb_description_en;
        $fastboatData->fb_description_idn = $request->fb_description_idn;
        $fastboatData->fb_content_en = $request->fb_content_en;
        $fastboatData->fb_content_idn = $request->fb_content_idn;
        $fastboatData->fb_term_condition_en = $request->fb_term_condition_en;
        $fastboatData->fb_term_condition_idn = $request->fb_term_condition_idn;
        $fastboatData->fb_updated_by = Auth()->id();

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

        // summernote
        $content = $request->fb_content_en;

        $dom = new DOMDocument();
        $dom->loadHTML($content, 9);

        $image = $dom->getElementsByTagName('img');

        foreach ($image as $key => $img) {
            $data = base64_decode(explode(',', explode(';', $img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time() . $key . '.png';
            file_put_contents(public_path() . $image_name, $data);

            $img->removeAttribute('src');
            $img->setAttribute('src', $image_name);
        }
        $content = $dom->saveHTML();


        $fastboatData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('fastboat.view');
    }

    // this function will get the $id of the selected data and then view the fast boat edit form
    public function edit($id)
    {
        $company = DataCompany::all();
        $fastboatEdit = DataFastboat::find($id);
        return view('data.fastboat.edit', compact('fastboatEdit', 'company'));
    }

    // this function will get the $id of the selected data and request data from input in fast boat edit from
    public function update(Request $request, $id)
    {

        // Handle insert data to database
        $fastboatData = DataFastboat::find($id);
        $fastboatData->fb_name = $request->fb_name;
        $fastboatData->fb_company = $request->fb_company;
        $fastboatData->fb_keywords = $request->fb_keywords;
        $fastboatData->fb_slug_en = $request->fb_slug_en;
        $fastboatData->fb_slug_idn = $request->fb_slug_idn;
        $fastboatData->fb_description_en = $request->fb_description_en;
        $fastboatData->fb_description_idn = $request->fb_description_idn;
        $fastboatData->fb_content_en = $request->fb_content_en;
        $fastboatData->fb_content_idn = $request->fb_content_idn;
        $fastboatData->fb_term_condition_en = $request->fb_term_condition_en;
        $fastboatData->fb_term_condition_idn = $request->fb_term_condition_idn;
        $fastboatData->fb_updated_by = Auth()->id();

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
        toast('Your data as been edited!', 'success');
        return redirect()->route('fastboat.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id)
    {
        $fastboatData = DataFastboat::find($id);
        $fastboatData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('fastboat.view');
    }

    // this function will get $id of selected data and view it in modal
    public function show($id)
    {
        $fastboatData = DataFastboat::with(['company'])->findOrFail($id);
        return response()->json($fastboatData);
    }

    // this function will get $id of selected data and change the status
    public function status($id)
    {
        $fastboatData = DataFastboat::find($id);

        if ($fastboatData) {
            if ($fastboatData->fb_status) {
                $fastboatData->fb_status = 0;
            } else {
                $fastboatData->fb_status = 1;
            }
            $fastboatData->save();
        }
        return back();
    }

}
