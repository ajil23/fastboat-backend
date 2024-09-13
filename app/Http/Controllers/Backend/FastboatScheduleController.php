<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\FastboatSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FastboatScheduleController extends Controller
{
    // Menampilkan halaman utama dari schedule
    public function index()
    {
        $scheduleData = FastboatSchedule::all();    // Mengambil seluruh data schedule
        $company = DataCompany::all();              // Mengambil seluruh data company
        $title = 'Delete Schedule Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('fast-boat.schedule.index', compact('scheduleData', 'company'));
    }

    // Menangani proses tambah data 
    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'sch_name' => 'required',
            'sch_company' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Menyimpan data inputan ke dalam database
        $scheduleData = new FastboatSchedule();
        $scheduleData->sch_company = $request->sch_company;
        $scheduleData->sch_name = $request->sch_name;
        $scheduleData->sch_updated_by = Auth()->id();
        $scheduleData->save();                              // Simpan data
        toast('Your data as been submited!', 'success');
        return redirect()->route('schedule.view');
    }

    // Menampilkan halaman edit data
    public function edit($sch_id)
    {
        $scheduleData = FastboatSchedule::find($sch_id);    // Mencari id dari data yang dipilih
        $company = DataCompany::all();                  // Mengambil seluruh data company
        return response()->json($scheduleData, $company);
    }

    // Menangani proses update data
    public function update(Request $request, $sch_id)
    {
        $schedule = FastboatSchedule::findOrFail($sch_id);  // Mencari id dari data yang dipilih
        $schedule->sch_company = $request->sch_company;
        $schedule->sch_name = $request->sch_name;
        $schedule->sch_updated_by = Auth()->id();
        $schedule->update();                                // Update data
        toast('Your data as been edited!', 'success');
        return redirect()->route('schedule.view');
    }

    // Menangani proses hapus data
    public function delete($sch_id)
    {
        $scheduleData = FastboatSchedule::find($sch_id);    // Mencari id dari data yang dipilih
        $scheduleData->delete();                            // Hapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('schedule.view');
    }
}
