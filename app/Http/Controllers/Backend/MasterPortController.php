<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterIsland;
use App\Models\MasterPort;
use DOMDocument;
use Illuminate\Http\Request;

class MasterPortController extends Controller
{
    // this function is for view all data from port table
    public function index()
    {
        $port = MasterPort::orderBy('prt_name_en', 'asc')->paginate(15);
        $island = MasterIsland::all();
        $title = 'Delete Port Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.port.index', compact('port', 'island'));
    }

    // this function is for view form to add port data
    public function add()
    {
        $island = MasterIsland::all();
        return view('master.port.add', compact('island'));
    }

    // this function will request data from input in port add form
    public function store(Request $request)
    {

        // Handle the request data validation
        $request->validate([
            'prt_name_en' => 'required|max:100',
            'prt_name_idn' => 'required|max:100',
            'prt_code' => 'required|max:100',
            'prt_slug_en' => 'required',
            'prt_slug_idn' => 'required',
            'prt_image1' => 'required|image',
            'prt_image2' => 'required|image',
            'prt_image3' => 'required|image',
            'prt_image4' => 'image',
            'prt_image5' => 'image',
            'prt_image6' => 'image',
            'prt_map' => 'required',
            'prt_description_en' => 'required',
            'prt_description_idn' => 'required',
            'prt_content_en' => 'required',
            'prt_content_idn' => 'required',
        ]);

        // Handle insert data to database
        $portData = new MasterPort();
        $portData->prt_name_en = $request->prt_name_en;
        $portData->prt_name_idn = $request->prt_name_idn;
        $portData->prt_island = $request->prt_island;
        $portData->prt_address = $request->prt_address;
        $portData->prt_code = $request->prt_code;
        $portData->prt_slug_en = $request->prt_slug_en;
        $portData->prt_slug_idn = $request->prt_slug_idn;
        $portData->prt_keyword = $request->prt_keyword;
        $portData->prt_map = $request->prt_map;
        $portData->prt_description_en = $request->prt_description_en;
        $portData->prt_description_idn = $request->prt_description_idn;
        $portData->prt_content_en = $request->prt_content_en;
        $portData->prt_content_idn = $request->prt_content_idn;
        $portData->prt_updated_by = Auth()->id();
        if ($request->hasFile('prt_image1')) {
            $portImage = $request->file('prt_image1')->store('prt_image1');
            $portData->prt_image1 = $portImage;
        }
        if ($request->hasFile('prt_image2')) {
            $portImage = $request->file('prt_image2')->store('prt_image2');
            $portData->prt_image2 = $portImage;
        }
        if ($request->hasFile('prt_image3')) {
            $portImage = $request->file('prt_image3')->store('prt_image3');
            $portData->prt_image3 = $portImage;
        }
        if ($request->hasFile('prt_image4')) {
            $portImage = $request->file('prt_image4')->store('prt_image4');
            $portData->prt_image4 = $portImage;
        }
        if ($request->hasFile('prt_image5')) {
            $portImage = $request->file('prt_image5')->store('prt_image5');
            $portData->prt_image5 = $portImage;
        }
        if ($request->hasFile('prt_image6')) {
            $portImage = $request->file('prt_image6')->store('prt_image6');
            $portData->prt_image6 = $portImage;
        }

        // summernote
        $content = $request->prt_content_en;

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

        $portData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('port.view');
    }

    // this function will get the $id of the selected data and then view the port edit form
    public function edit($id)
    {
        $portEdit = MasterPort::find($id);
        $island = MasterIsland::all();
        return view('master.port.edit', compact('portEdit', 'island'));
    }

    // this function will get the $id of the selected data and request data from input in port edit from
    public function update(Request $request, $id)
    {

        // Handle update data to database
        $portData = MasterPort::find($id);
        $portData->prt_name_en = $request->prt_name_en;
        $portData->prt_name_idn = $request->prt_name_idn;
        $portData->prt_island = $request->prt_island;
        $portData->prt_address = $request->prt_address;
        $portData->prt_code = $request->prt_code;
        $portData->prt_slug_en = $request->prt_slug_en;
        $portData->prt_slug_idn = $request->prt_slug_idn;
        $portData->prt_keyword = $request->prt_keyword;
        $portData->prt_map = $request->prt_map;
        $portData->prt_description_en = $request->prt_description_en;
        $portData->prt_description_idn = $request->prt_description_idn;
        $portData->prt_content_en = $request->prt_content_en;
        $portData->prt_content_idn = $request->prt_content_idn;
        $portData->prt_updated_by = Auth()->id();
        if ($request->hasFile('prt_image1')) {
            $portImage = $request->file('prt_image1')->store('prt_image1');
            $portData->prt_image1 = $portImage;
        }
        if ($request->hasFile('prt_image2')) {
            $portImage = $request->file('prt_image2')->store('prt_image2');
            $portData->prt_image2 = $portImage;
        }
        if ($request->hasFile('prt_image3')) {
            $portImage = $request->file('prt_image3')->store('prt_image3');
            $portData->prt_image3 = $portImage;
        }
        if ($request->hasFile('prt_image4')) {
            $portImage = $request->file('prt_image4')->store('prt_image4');
            $portData->prt_image4 = $portImage;
        }
        if ($request->hasFile('prt_image5')) {
            $portImage = $request->file('prt_image5')->store('prt_image5');
            $portData->prt_image5 = $portImage;
        }
        if ($request->hasFile('prt_image6')) {
            $portImage = $request->file('prt_image6')->store('prt_image6');
            $portData->prt_image6 = $portImage;
        }
        $portData->save();
        toast('Your data as been edited!', 'success');
        return redirect()->route('port.view');
    }

    // this function will get the $id of selected data and do delete operation
    public function delete($id)
    {
        $portDelete = MasterPort::find($id);
        $portDelete->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('port.view');
    }

    // this function will get $id of selected data and view it in modal
    public function show($id)
    {
        $portData = MasterPort::with(['island'])->find($id);
        return response()->json($portData);
    }
}
