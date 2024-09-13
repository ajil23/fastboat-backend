<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\MasterPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterPaymentMethodController extends Controller
{
    // Menampilkan halaman utama untuk menu payment method
    public function index()
    {
        $payment = MasterPaymentMethod::all();  // Mengambil seluruh data payment method
        $title = 'Delete Payment Methode Data!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('master.payment-method.index', compact('payment'));
    }

    public function store(Request $request)
    {

        // Validasi inputan
        $validator = Validator::make($request->all(), [
            'py_name' => 'required',
            'py_value' => 'required'
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
        $paymentData = new MasterPaymentMethod();
        $paymentData->py_name = $request->py_name;
        $paymentData->py_value = $request->py_value;
        $paymentData->py_updated_by = Auth()->id();
        $paymentData->save();
        toast('Your data as been submited!', 'success');
        return redirect()->route('payment-method.view');
    }

    // Menangani proses update data 
    public function update(Request $request, $py_id)
    {
        // Proses menyimpan inputan ke dalam database
        $paymentData = MasterPaymentMethod::find($py_id); // Mengambil id dari data yang di pilih
        $paymentData->py_name = $request->py_name;
        $paymentData->py_value = $request->py_value;
        $paymentData->py_updated_by = Auth()->id();
        $paymentData->update();
        toast('Your data as been edited!', 'success');
        return redirect()->route('payment-method.view');
    }

    // Menangani proses hapus data
    public function delete($py_id)
    {
        $paymentDelete = MasterPaymentMethod::find($py_id); // Mengambil id dari data yang di pilih
        $paymentDelete->delete();                           // Menjalankan proses hapus data
        toast('Your data as been deleted!', 'success');
        return redirect()->route('payment-method.view');
    }
}
