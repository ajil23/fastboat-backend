<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\MasterPaymentMethod;
use Illuminate\Http\Request;

class BookingTrashController extends Controller
{
    public function index()
    {
        $bookingData = BookingData::orderBy('created_at', 'desc')->get();
        $paymentMethod = MasterPaymentMethod::all();
        return view('booking.trash.index', compact('bookingData', 'paymentMethod'));
    }

    // Menangani perubahan status 
    public function status($fbo_id)
    {
        $bookingData = BookingData::find($fbo_id);  // Ambil data berdasarkan ID

        if ($bookingData && $bookingData->fbo_payment_status == 'paid') {  // Cek jika payment sudah 'paid'
            // Proses pengubahan status transaksi
            if ($bookingData->fbo_transaction_status == 'waiting') {
                $bookingData->fbo_transaction_status = 'accepted';
            } elseif ($bookingData->fbo_transaction_status == 'accepted') {
                $bookingData->fbo_transaction_status = 'confirmed';
            } elseif ($bookingData->fbo_transaction_status == 'confirmed') {
                $bookingData->fbo_transaction_status = 'accepted';  // Jika confirmed, kembalikan ke accepted
            }
            $bookingData->save();
        }

        return back();
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'fbo_id' => 'required',
            'fbo_payment_method' => 'required|string',
            'fbo_transaction_id' => 'required|string',
        ]);

        $bookingData = BookingData::find($request->fbo_id);

        if ($bookingData) {
            $bookingData->fbo_payment_status = 'paid';
            $bookingData->fbo_payment_method = $request->fbo_payment_method;
            $bookingData->fbo_transaction_id = $request->fbo_transaction_id;
            $bookingData->fbo_transaction_status = 'accepted';
            $bookingData->save();
        }

        return redirect()->back()->with('success', 'Payment status updated to paid.');
    }
}
