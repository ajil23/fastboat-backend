<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterPayment;
use Illuminate\Http\Request;

class MasterPaymentController extends Controller
{
    public function index(){
        $payment = MasterPayment::all();
        $title = 'Delete Island Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.payment.index', compact('payment'));
    }

    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'py_name' => 'required',
            'py_value' => 'required'
        ]);

        // Handle insert data to database
        $paymentData = new MasterPayment();
        $paymentData->py_name = $request->py_name;
        $paymentData->py_value = $request->py_value;
        $paymentData->py_updated_by = Auth()->id();
        $paymentData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('payment.view');
    }

    public function update(Request $request, $py_id){
        $paymentData = MasterPayment::find($py_id);
        $paymentData->py_name = $request->py_name;
        $paymentData->py_value = $request->py_value;
        $paymentData->py_updated_by = Auth()->id();
        $paymentData->save();
        toast('Your data as been edited!', 'success');
        return redirect()->route('payment.view');
    }

    public function delete($py_id){
        $paymentDelete = MasterPayment::find($py_id);
        $paymentDelete->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('payment.view');
    }
}
