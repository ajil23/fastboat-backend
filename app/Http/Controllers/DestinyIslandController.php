<?php

namespace App\Http\Controllers;

use App\Models\DestinyIsland;
use DOMDocument;
use Illuminate\Http\Request;

class DestinyIslandController extends Controller
{
    // this function is for view all data from island table
    public function index () {
        $island = DestinyIsland::all();
        return view('destiny.island.index', compact('island'));
    }

    // this function is for view form to add island data
    public function add () {
        return view('destiny.island.add');
    }

    // this function will request data from input in island add form
    public function store (Request $request) {
        
        // Handle the request data validation
        $request->validate([
            'isd_name' => 'required|max:100',
            'isd_code' => 'required|max:100',
            'isd_slug_en' => 'required',
            'isd_slug_idn' => 'required',
            'isd_image1' => 'required|image',
            'isd_image2' => 'required|image',
            'isd_image3' => 'required|image',
            'isd_image4' => 'image',
            'isd_image5' => 'image',
            'isd_image6' => 'image',
            'isd_map' => 'required',
            'isd_description_en' => 'required',
            'isd_description_idn' => 'required',
            'isd_content_en' => 'required',
            'isd_content_idn' => 'required',
        ]);

        // Handle insert data to database
        $islandData = new DestinyIsland();
        $islandData->isd_name = $request->isd_name;
        $islandData->isd_code = $request->isd_code;
        $islandData->isd_slug_en = $request->isd_slug_en;
        $islandData->isd_slug_idn = $request->isd_slug_idn;
        $islandData->isd_keyword = $request->isd_keyword;
        $islandData->isd_map = $request->isd_map;
        $islandData->isd_description_en = $request->isd_description_en;
        $islandData->isd_description_idn = $request->isd_description_idn;
        $islandData->isd_content_en = $request->isd_content_en;
        $islandData->isd_content_idn = $request->isd_content_idn;
        $islandData->isd_updated_by = Auth()->id();
        if ($request->hasFile('isd_image1')) {
            $islandImage = $request->file('isd_image1')->store('isd_image1');
            $islandData->isd_image1 = $islandImage;
        }
        if ($request->hasFile('isd_image2')) {
            $islandImage = $request->file('isd_image2')->store('isd_image2');
            $islandData->isd_image2 = $islandImage;
        }
        if ($request->hasFile('isd_image3')) {
            $islandImage = $request->file('isd_image3')->store('isd_image3');
            $islandData->isd_image3 = $islandImage;
        }
        if ($request->hasFile('isd_image4')) {
            $islandImage = $request->file('isd_image4')->store('isd_image4');
            $islandData->isd_image4 = $islandImage;
        }
        if ($request->hasFile('isd_image5')) {
            $islandImage = $request->file('isd_image5')->store('isd_image5');
            $islandData->isd_image5 = $islandImage;
        }
        if ($request->hasFile('isd_image6')) {
            $islandImage = $request->file('isd_image6')->store('isd_image6');
            $islandData->isd_image6 = $islandImage;
        }

        // summernote
        $content_en = $request->isd_content_en;

        $dom = new DOMDocument();
        $dom->loadHTML($content_en,9);

        $image = $dom->getElementsByTagName('img');

        foreach ($image as $key => $img) {
            $data = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time(). $key.'.png';
            file_put_contents(public_path().$image_name,$data);

            $img->removeAttribute('src');
            $img->setAttribute('src',$image_name);
        }
        $content_en = $dom->saveHTML();
        

        $islandData->save();
        toast('Your data as been submited!','success');
        return redirect()->route('island.view');
    }

    // this function will get the $id of the selected data and then view the island edit form
    public function edit ($id) {
        $islandEdit = DestinyIsland::find($id);
        return view ('destiny.island.edit', compact('islandEdit'));
    }

    // this function will get the $id of the selected data and request data from input in island edit from
    public function update (Request $request, $id) {

         // Handle update data to database
        $islandData = DestinyIsland::find($id);
        $islandData->isd_name = $request->isd_name;
        $islandData->isd_code = $request->isd_code;
        $islandData->isd_slug_en = $request->isd_slug_en;
        $islandData->isd_slug_idn = $request->isd_slug_idn;
        $islandData->isd_keyword = $request->isd_keyword;
        $islandData->isd_map = $request->isd_map;
        $islandData->isd_description_en = $request->isd_description_en;
        $islandData->isd_description_idn = $request->isd_description_idn;
        $islandData->isd_content_en = $request->isd_content_en;
        $islandData->isd_content_idn = $request->isd_content_idn;
        $islandData->isd_updated_by = Auth()->id();
        if ($request->hasFile('isd_image1')) {
            $islandImage = $request->file('isd_image1')->store('isd_image1');
            $islandData->isd_image1 = $islandImage;
        }
        if ($request->hasFile('isd_image2')) {
            $islandImage = $request->file('isd_image2')->store('isd_image2');
            $islandData->isd_image2 = $islandImage;
        }
        if ($request->hasFile('isd_image3')) {
            $islandImage = $request->file('isd_image3')->store('isd_image3');
            $islandData->isd_image3 = $islandImage;
        }
        if ($request->hasFile('isd_image4')) {
            $islandImage = $request->file('isd_image4')->store('isd_image4');
            $islandData->isd_image4 = $islandImage;
        }
        if ($request->hasFile('isd_image5')) {
            $islandImage = $request->file('isd_image5')->store('isd_image5');
            $islandData->isd_image5 = $islandImage;
        }
        if ($request->hasFile('isd_image6')) {
            $islandImage = $request->file('isd_image6')->store('isd_image6');
            $islandData->isd_image6 = $islandImage;
        }
        $islandData->save();
         toast('Your data as been edited!','success');
         return redirect()->route('island.view');
    }
    
    // this function will get the $id of selected data and do delete operation
    public function delete($id){
        $islandDelete = DestinyIsland::find($id);
        $islandDelete->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('island.view');
    }

    // this function will get $id of selected data and view it in modal
    public function show($id){
        $islandData = DestinyIsland::find($id);
        return response()->json($islandData);
    }
}
