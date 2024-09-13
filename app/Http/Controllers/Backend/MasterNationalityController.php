<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterNationality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterNationalityController extends Controller
{
    // Menampilkan halaman utama dari menu nationality
    public function index()
    {
        // Mengambil seluruh data nationality dengan paginasi 25
        $nationality = MasterNationality::paginate(25);
        $title = 'Delete Nationality Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.nationality.index', compact('nationality'));
    }

    // Menangani proses simpan data
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'nas_country' => 'required',
            'nas_country_code' => 'required'
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Meyimpan inputan ke dalam database
        $natData = new MasterNationality();
        $natData->nas_country = $request->nas_country;
        $natData->nas_country_code = $request->nas_country_code;
        $natData->save();   // Simpan data ke database
        toast('Your data as been submited!', 'success');
        return redirect()->route('nationality.view');
    }

    // Menangani proses update data
    public function update(Request $request, $nas_id)
    {
        $natData = MasterNationality::find($nas_id);    // Mengambil id dari data yang dipilih
        $natData->nas_country = $request->nas_country;
        $natData->nas_country_code = $request->nas_country_code;
        $natData->update();                             // Update data 
        toast('Your data as been edited!', 'success');
        return redirect()->route('nationality.view');
    }

    // Menangani proses hapus data
    public function delete($nas_id)
    {
        $natDelete = MasterNationality::find($nas_id);  // Mengambil id dari data yang dipilih
        $natDelete->delete();                           // Hapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('nationality.view');
    }
}
