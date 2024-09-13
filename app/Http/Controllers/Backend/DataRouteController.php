<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataRoute;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DataRouteController extends Controller
{
    // Menampilkan halaman utama menu route
    public function index()
    {
        $route = DataRoute::all();  // Mengambil seluruh data route
        $title = 'Delete Route Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('data.route.index', compact('route'));
    }

    // Menangani proses simpan data ke database
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'rt_dept_island' => 'required',
            'rt_arrival_island' => 'required'
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menyimpan inputan ke dalam database
        $routeData = new DataRoute();
        $routeData->rt_dept_island = $request->rt_dept_island;
        $routeData->rt_arrival_island = $request->rt_arrival_island;
        $routeData->rt_updated_by = Auth()->id();
        $routeData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('route.view');
    }

    // Menangani proses update data
    public function update(Request $request, $rt_id)
    {
        // Menyimpan inputan ke dalam database
        $routeData = DataRoute::find($rt_id);          // Mencari id dari data yang di pilih
        $routeData->rt_dept_island = $request->rt_dept_island;
        $routeData->rt_arrival_island = $request->rt_arrival_island;
        $routeData->rt_updated_by = Auth()->id();
        $routeData->save();
        toast('Your data as been edited!', 'success');
        return redirect()->route('route.view');
    }

    // Menangani proses hapus data
    public function delete($rt_id)
    {
        $routeDelete = DataRoute::find($rt_id);    // Mencari id dari data yang di pilih
        $routeDelete->delete();                 // Menjalankan operasi hapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('route.view');
    }
}
