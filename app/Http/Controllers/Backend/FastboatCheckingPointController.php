<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\FastboatCheckingPoint;
use App\Models\MasterPort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FastboatCheckingPointController extends Controller
{
    public function index(){
        $checking = FastboatCheckingPoint::all();  // Mengambil seluruh data checking point
        $company = DataCompany::all();
        $departure = MasterPort::all();
        $title = 'Delete Checking Point Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('fast-boat.checking-point.index', compact('checking', 'company', 'departure'));
    }

    public function store(Request $request){
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
        $checkingData = new FastboatCheckingPoint();
        $checkingData->fcp_company = $request->fcp_company;
        $checkingData->fcp_dept = $request->fcp_dept;
        $checkingData->fcp_address = $request->fcp_address;
        $checkingData->fcp_maps = $request->fcp_maps;
        $checkingData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('checking.view');
    }
    
    public function update(Request $request, $fcp_id){
        $checkingUpdate = FastboatCheckingPoint::find($fcp_id);
        $checkingUpdate->fcp_company = $request->fcp_company;
        $checkingUpdate->fcp_dept = $request->fcp_dept;
        $checkingUpdate->fcp_address = $request->fcp_address;
        $checkingUpdate->fcp_maps = $request->fcp_maps;
        $checkingUpdate->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('checking.view');
    }
    
    public function delete($fcp_id){
        $checkingDelete = FastboatCheckingPoint::find($fcp_id);
        $checkingDelete -> delete();
        toast('Your data as been submited!', 'success');
        return redirect()->route('checking.view');
    }
}
