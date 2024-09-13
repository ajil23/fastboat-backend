<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FastboatShuttleArea;
use App\Models\MasterIsland;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FastboatShuttleAreaController extends Controller
{
    // Menampilkan halaman utama menu shuttle area
    public function index()
    {
        // Mengambil seluruh data shuttle area dengan paginasi 10
        $shuttlearea = FastboatShuttleArea::paginate(10);
        $island = MasterIsland::all();  // Mengambil seluruh data island
        $title = 'Delete Shuttle Area Data Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('fast-boat.shuttlearea.index', compact('shuttlearea', 'island'));
    }

    // Menangani proses simpan data
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'sa_name' => 'required',
            'sa_island' => 'required',
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
        $shuttleareaData = new FastboatShuttleArea();
        $shuttleareaData->sa_island = $request->sa_island;
        $shuttleareaData->sa_name = $request->sa_name;
        $shuttleareaData->sa_updated_by = Auth()->id();
        $shuttleareaData->save();                               // Simpan data
        toast('Your data as been submited!', 'success');
        return redirect()->route('shuttlearea.view');
    }

    // Menampilkan halaman edit data
    public function edit($sa_id)
    {
        $shuttleareaData = FastboatShuttleArea::find($sa_id);   // Mencari id dari data yang dipilih
        $island = MasterIsland::all();                          // Mengambil seluruh data island
        return view('fast-boat.shuttlearea.edit', compact('shuttleareaData', 'island'));
    }

    // Menangani proses update data 
    public function update(Request $request, $sa_id)
    {
        // Menyimpan data dari inputan ke dalam database
        $shuttleareaData = FastboatShuttleArea::find($sa_id);   // Mencari id dari data yang dipilih
        $shuttleareaData->sa_island = $request->sa_island;
        $shuttleareaData->sa_name = $request->sa_name;
        $shuttleareaData->sa_updated_by = Auth()->id();
        $shuttleareaData->update();                             // Update data
        toast('Your data as been edited!', 'success');
        return redirect()->route('shuttlearea.view');
    }

    // Menangani proses hapus data
    public function delete($sa_id)
    {
        $scheduleData = FastboatShuttleArea::find($sa_id);  // Mencari id dari data yang dipilih
        $scheduleData->delete();                            // Simpan data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('schedule.view');
    }
}
