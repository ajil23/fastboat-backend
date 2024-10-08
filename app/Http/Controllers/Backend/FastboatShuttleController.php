<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\FastboatShuttle;
use App\Models\FastboatShuttleArea;
use App\Models\FastboatTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FastboatShuttleController extends Controller
{
    // Menampilkan halaman utama untuk menu shuttle
    public function index(){
        // Mengambil seluruh data shuttle dengan trip, schedule, dan company 
        // Mengurutkan secara ascending berdasarkan s_trip
        $shuttleData = FastboatShuttle::with(['trip.schedule.company'])->orderBy('s_trip', 'asc')->orderBy('s_start', 'asc')->get();
        $trip = FastboatTrip::with(['departure.arrival']);  // Mengambil seluruh data trip
        $area = FastboatShuttleArea::all();                 // Mengambil seluruh data shuttle area
        $company = DataCompany::all();                      // Mengambil seluruh data company
        $title = 'Delete Shuttle Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('fast-boat.shuttle.index', compact('shuttleData', 'trip', 'area', 'company'));
    }

    // Menampilkan halaman tambah data
    public function add()
    {
        // Menampilkan data trip yang memiliki shuttle option (pickup / dropoff)
        $trip = FastboatTrip::whereIn('fbt_shuttle_option', ['pickup', 'drop'])->get();
        $area = FastboatShuttleArea::all();
        $company = DataCompany::orderBy('cpn_name', 'asc')->get();
        return view('fast-boat.shuttle.add', compact('trip', 'area', 'company'));
    }

    // Menangani proses simpan data ke dalam database
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            's_trip' => 'required|array',
            's_trip.*' => 'required|string|max:255',
            's_area' => 'required|array',
            's_area.*' => 'required|string|max:255',
            's_start' => 'nullable|array',
            's_start.*' => 'nullable|date_format:H:i',
            's_end' => 'nullable|array',
            's_end.*' => 'nullable|date_format:H:i|after:s_start.*',
            's_meeting_point' => 'nullable|array',
            's_meeting_point.*' => 'nullable|string|max:255',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Looping melalui array trip
        foreach ($request->s_trip as $tripIndex => $trip) {
            // Looping melalui array shuttle info menggunakan foreach
            foreach ($request->s_area as $areaIndex => $area) {
                // Cek apakah waktu tidak diisi dan set nilai "Not Set" jika kosong
                $s_start = $request->s_start[$areaIndex] ?? 'Not Set';
                $s_end = $request->s_end[$areaIndex] ?? 'Not Set';
                $s_meeting_point = $request->s_meeting_point[$areaIndex] ?? 'not_set';

                // Cek apakah data sudah ada di database
                $existingData = FastboatShuttle::where([
                    ['s_trip', $trip],
                    ['s_area', $area],
                ])->first();

                if ($existingData) {
                    // Update data yang sudah ada
                    $existingData->update([
                        's_trip' => $trip,
                        's_area' => $area,
                        's_start' => $s_start,
                        's_end' => $s_end,
                        's_meeting_point' => $s_meeting_point,
                        's_updated_by' => auth()->id(),
                    ]);
                } else {
                    // Buat data baru
                    $shuttleData = new FastboatShuttle();
                    $shuttleData->s_trip = $trip;
                    $shuttleData->s_area = $area;
                    $shuttleData->s_start = $s_start;
                    $shuttleData->s_end = $s_end;
                    $shuttleData->s_meeting_point = $s_meeting_point;
                    $shuttleData->s_updated_by = auth()->id();
                    $shuttleData->save();
                }
            }
        }

        // Menambahkan pesan toast sukses ke dalam session
        toast('Your data has been submitted!', 'success');
        return redirect()->route('shuttle.view');
    }

    // Menangani update data dengan multiple select
    public function multiple(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        $s_start = $request->input('s_start', null);
        $s_end = $request->input('s_end', null);
        $s_meeting_point = $request->input('s_meeting_point', null);
    
        foreach ($selectedIds as $id) {
            $shuttleData = FastboatShuttle::find($id);
            if ($shuttleData) {
                if (!is_null($s_start)) {
                    $shuttleData->s_start = $s_start;
                }
                if (!is_null($s_end)) {
                    $shuttleData->s_end = $s_end;
                }
                if (!is_null($s_meeting_point)) {
                    $shuttleData->s_meeting_point = $s_meeting_point;
                }
                $shuttleData->update();   // Update data
            }
        }
    
        return redirect()->back()->with('success', 'Selected items updated successfully.');
    }
    
    
    // Menangani hapus data dengan multiple select
    public function deleteMultiple(Request $request)
    {
        $selectedIds = explode(',', $request->input('selected_ids', ''));
        FastboatShuttle::whereIn('s_id', $selectedIds)->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('shuttle.view');
    }
}
