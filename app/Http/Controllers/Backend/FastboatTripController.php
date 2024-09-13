<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataFastboat;
use App\Models\DataRoute;
use App\Models\FastboatSchedule;
use App\Models\FastboatTrip;
use App\Models\MasterPort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FastboatTripController extends Controller
{
    // Menampilkan halaman utama menu fast-boat trip
    public function index()
    {
        $trip = FastboatTrip::all();                // Mengambil seluruh data yang ada pada tabel fast-boat.
        $title = 'Delete Fastboat Trip!';           // Title untuk modal konfirmasi hapus data.
        $text = "Are you sure you want to delete?"; // Text untuk modal konfirmasi hapus data.
        confirmDelete($title, $text);               // Inisiasi modal konfirmasi hapus data.

        // Tambahkan data-confirm-delete="true" agar modal dapat muncul.
        return view('fast-boat.trip.index', compact('trip'));
    }

    // Menampilkan halaman tambah data trip
    public function add()
    {
        $route = DataRoute::all();              // Mengambil seluruh data pada tabel route
        $fastboat = DataFastboat::all();        // Mengambil seluruh data pada tabel fast-boat
        $schedule = FastboatSchedule::all();    // Mengambil seluruh data pada tabel schedule
        $departure = MasterPort::all();         // Mengambil seluruh data pada tabel port sebagai opsi departure port
        $arrival = MasterPort::all();           // Mengambil seluruh data pada tabel port sebagai opsi arrival port
        return view('fast-boat.trip.add', compact('route', 'fastboat', 'schedule', 'departure', 'arrival'));
    }

    // Menangani proses tambah data ke database
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'fbt_route' => 'required',
            'fbt_fastboat' => 'required',
            'fbt_schedule' => 'required',
            'fbt_dept_port' => 'required',
            'fbt_dept_time' => 'required',
            'fbt_time_limit' => 'required',
            'fbt_arrival_port' => 'required',
            'fbt_arrival_time' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        // Get route & schedule id
        $fbt_route_id = $request->fbt_route;
        $fbt_fastboat_id = $request->fbt_fastboat;

        // Mencari route & schedule id
        $fbtrip = DataRoute::findOrFail($fbt_route_id);
        $fbtfastboat = DataFastboat::findOrFail($fbt_fastboat_id);

        // Mengirim data ke database
        $tripData = new FastboatTrip();
        $tripData->fbt_name = $fbtfastboat->fb_name . ' for ' . $fbtrip->rt_dept_island . ' to ' . $fbtrip->rt_arrival_island;
        $tripData->fbt_route = $request->fbt_route;
        $tripData->fbt_fastboat = $request->fbt_fastboat;
        $tripData->fbt_schedule = $request->fbt_schedule;
        $tripData->fbt_dept_port = $request->fbt_dept_port;
        $tripData->fbt_dept_time = $request->fbt_dept_time;
        $tripData->fbt_time_limit = $request->fbt_time_limit;
        $tripData->fbt_time_gap = $request->fbt_time_gap;
        $tripData->fbt_arrival_port = $request->fbt_arrival_port;
        $tripData->fbt_arrival_time = $request->fbt_arrival_time;
        $tripData->fbt_info_en = $request->fbt_info_en;
        $tripData->fbt_info_idn = $request->fbt_info_idn;
        $tripData->fbt_shuttle_type = $request->fbt_shuttle_type;
        $tripData->fbt_shuttle_option = $request->fbt_shuttle_option;
        $tripData->fbt_updated_by = Auth()->id();
        $tripData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('trip.view');
    }

    // Menampilkan halaman edit data trip
    public function edit($id)
    {
        $tripEdit = FastboatTrip::find($id);    // Mengambil id dari data yang dipilih
        $route = DataRoute::all();              // Mengambil seluruh data pada tabel route
        $fastboat = DataFastboat::all();        // Mengambil seluruh data pada tabel fast-boat
        $schedule = FastboatSchedule::all();    // Mengambil seluruh data pada tabel schedule
        $departure = MasterPort::all();         // Mengambil seluruh data pada tabel port sebagai opsi departure port
        $arrival = MasterPort::all();           // Mengambil seluruh data pada tabel port sebagai opsi arrival port

        // Penggunaan compact untuk dapat mengirimkan seluruh variabel di atas ke halaman edit data trip
        return view('fast-boat.trip.edit', compact('tripEdit', 'route', 'fastboat', 'schedule', 'departure', 'arrival'));
    }


    // Menangani proses update data ke database
    public function update(Request $request, $fbt_id)
    {
        $tripData = FastboatTrip::find($fbt_id);        // Mengambil id dari data yang di pilih
        $tripData->fbt_name = $request->fbt_name;
        $tripData->fbt_route = $request->fbt_route;
        $tripData->fbt_fastboat = $request->fbt_fastboat;
        $tripData->fbt_schedule = $request->fbt_schedule;
        $tripData->fbt_dept_port = $request->fbt_dept_port;
        $tripData->fbt_dept_time = $request->fbt_dept_time;
        $tripData->fbt_time_limit = $request->fbt_time_limit;
        $tripData->fbt_time_gap = $request->fbt_time_gap;
        $tripData->fbt_arrival_port = $request->fbt_arrival_port;
        $tripData->fbt_arrival_time = $request->fbt_arrival_time;
        $tripData->fbt_info_en = $request->fbt_info_en;
        $tripData->fbt_info_idn = $request->fbt_info_idn;
        $tripData->fbt_shuttle_type = $request->fbt_shuttle_type;
        $tripData->fbt_shuttle_option = $request->fbt_shuttle_option;
        $tripData->fbt_updated_by = Auth()->id();
        $tripData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('trip.view');
    }

    // Melakukan operasi hapus data
    public function delete($fbt_id)
    {
        $tripData = FastboatTrip::find($fbt_id);
        $tripData->delete();
        toast('Your data as been deleted!', 'success');
        return redirect()->route('trip.view');
    }

    // Menampilkan data ke dalam modal
    public function show($fbt_id)
    {
        $tripData = FastboatTrip::with(['route', 'fastboat', 'schedule', 'departure', 'arrival'])->findOrFail($fbt_id);
        // Format waktu tanpa detik
        $tripData->fbt_dept_time = \Carbon\Carbon::parse($tripData->fbt_dept_time)->format('H:i');
        $tripData->fbt_time_limit = \Carbon\Carbon::parse($tripData->fbt_time_limit)->format('H:i');
        $tripData->fbt_time_gap = \Carbon\Carbon::parse($tripData->fbt_time_gap)->format('H:i');
        $tripData->fbt_arrival_time = \Carbon\Carbon::parse($tripData->fbt_arrival_time)->format('H:i');
        return response()->json($tripData);
    }


    // Menangani perubahan status trip 
    public function status($fbt_id)
    {
        $tripData = FastboatTrip::find($fbt_id);

        if ($tripData) {
            if ($tripData->fbt_status) {
                $tripData->fbt_status = 0;
            } else {
                $tripData->fbt_status = 1;
            }
            $tripData->save();
        }
        return back();
    }

    // Menangani perbuahan status rekomendasi trip
    public function recommend($fbt_id)
    {
        $tripData = FastboatTrip::find($fbt_id);

        if ($tripData) {
            if ($tripData->fbt_recom) {
                $tripData->fbt_recom = 0;
            } else {
                $tripData->fbt_recom = 1;
            }
            $tripData->save();
        }
        return back();
    }
}
