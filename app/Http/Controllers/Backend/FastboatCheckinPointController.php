<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\FastboatCheckinPoint;
use App\Models\MasterPort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FastboatCheckinPointController extends Controller
{

    public function index()
    {
        $checkin = FastboatCheckinPoint::all();  // Mengambil seluruh data checkin point
        $company = DataCompany::orderBy('cpn_name', 'asc')->having('cpn_type', 'fast_boat')->get();
        $departure = MasterPort::orderBy('prt_name_en', 'asc')->get();
        $title = 'Delete Checkin Point Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('fast-boat.checkin-point.index', compact('checkin', 'company', 'departure'));
    }

    public function store(Request $request)
    {
        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'fcp_company' => 'required',
            'fcp_dept' => 'required',
            'fcp_address' => 'required',
            'fcp_maps' => 'required',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Proses menyimpan inputan ke dalam database
        $checkinData = new FastboatCheckinPoint();
        $checkinData->fcp_company = $request->fcp_company;
        $checkinData->fcp_dept = $request->fcp_dept;
        $checkinData->fcp_address = $request->fcp_address;
        $checkinData->fcp_maps = $request->fcp_maps;
        $checkinData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('checkin.view');
    }

    public function update(Request $request, $fcp_id)
    {
        $checkinUpdate = FastboatCheckinPoint::find($fcp_id);
        $checkinUpdate->fcp_company = $request->fcp_company;
        $checkinUpdate->fcp_dept = $request->fcp_dept;
        $checkinUpdate->fcp_address = $request->fcp_address;
        $checkinUpdate->fcp_maps = $request->fcp_maps;
        $checkinUpdate->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('checkin.view');
    }

    public function delete($fcp_id)
    {
        $checkinDelete = FastboatCheckinPoint::find($fcp_id);
        $checkinDelete->delete();
        toast('Your data as been submited!', 'success');
        return redirect()->route('checkin.view');
    }

    public function show($fcp_id)
    {
        $pointData = FastboatCheckinPoint::with(['company', 'departure'])->find($fcp_id);  // Mengambil id dari data yang dipilih
        return response()->json($pointData);
    }
}
