<?php

namespace App\Http\Controllers;

use App\Models\DestinyIsland;
use App\Models\DestinyPort;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class DestinyPortController extends Controller
{
    public function index () {
        $port = DestinyPort::all();
        $data = DestinyIsland::all();
        return view('destiny.port.index', compact('port', 'data'));
    }

    // this function is for view form to add port data
    public function add () {
        $data = DestinyIsland::all();
        return view('destiny.port.add', compact('data'));
    }

    public function store (Request $request) {
        
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

        $portData = new DestinyPort();
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
        toast('Your data as been submited!','success');
        return redirect()->route('port.view');
    }

    public function edit ($id) {
        $portEdit = DestinyPort::find($id);
        $data = DestinyIsland::all();
        return view('destiny.port.edit', compact('portEdit', 'data'));
    }

    public function update (Request $request, $id) {
        $portData = DestinyPort::find($id);
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
         toast('Your data as been edited!','success');
        return redirect()->route('port.view');
    }

    public function delete ($id) {
        $portData = DestinyPort::find($id);
        $portData->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('port.view');
    }

    public function show($id){
        $portData = DestinyPort::find($id);
        return response()->json($portData);
    }
}
