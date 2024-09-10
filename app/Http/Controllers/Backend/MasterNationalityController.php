<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterNationality;
use Illuminate\Http\Request;

class MasterNationalityController extends Controller
{
    public function index()
    {
        $nationality = MasterNationality::all();
        $title = 'Delete Nationality Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.nationality.index', compact('nationality'));
    }

    public function store(Request $request)
    {
        // Menangani validasi data yang dikirim
        $request->validate([
            'nas_country' => 'required',
            'nas_country_code' => 'required'
        ]);

        // Handle insert data to database
        $natData = new MasterNationality();
        $natData->nas_country = $request->nas_country;
        $natData->nas_country_code = $request->nas_country_code;
        $natData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('nationality.view');
    }

    public function update(Request $request, $nas_id) {
        $natData = MasterNationality::find($nas_id);
        $natData->nas_country = $request->nas_country;
        $natData->nas_country_code = $request->nas_country_code;
        $natData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('nationality.view');
    }

    public function delete($nas_id) {
        $natDelete = MasterNationality::find($nas_id);
        $natDelete->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('nationality.view');
    }
}
