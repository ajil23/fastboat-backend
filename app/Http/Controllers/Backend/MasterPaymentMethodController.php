<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterPaymentMethod;
use Illuminate\Http\Request;

class MasterPaymentMethodController extends Controller
{
    public function index(){
        $payment = MasterPaymentMethod::all();
        $title = 'Delete Payment Methode Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.payment-method.index', compact('payment'));
    }

    public function store(Request $request){
        // Handle the request data validation
        $request->validate([
            'py_name' => 'required',
            'py_value' => 'required'
        ]);

        // Handle insert data to database
        $paymentData = new MasterPaymentMethod();
        $paymentData->py_name = $request->py_name;
        $paymentData->py_value = $request->py_value;
        $paymentData->py_updated_by = Auth()->id();
        $paymentData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('payment-method.view');
    }

    public function update(Request $request, $py_id){
        $paymentData = MasterPaymentMethod::find($py_id);
        $paymentData->py_name = $request->py_name;
        $paymentData->py_value = $request->py_value;
        $paymentData->py_updated_by = Auth()->id();
        $paymentData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('payment-method.view');
    }

    public function delete($py_id){
        $paymentDelete = MasterPaymentMethod::find($py_id);
        $paymentDelete->delete();
        toast('Your data as been deleted!','success');
        return redirect()->route('payment-method.view');
    }
}
