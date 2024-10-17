<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\MasterPaymentMethod;
use Carbon\Carbon;
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

        // Menampilkan modal detail data fast-boat
        public function show($fbo_id)
        {
            $bookingData = BookingData::with(['trip.fastboat', 'trip.departure.island', 'trip.arrival.island', 'contact', 'checkPoint'])
                ->find($fbo_id);
    
            if (!$bookingData) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }
    
            $passengerDataString = $bookingData->fbo_passenger; // Mengambil data dari database
            $passengerArray = [];
        
            // Memisahkan data berdasarkan ';' untuk setiap penumpang
            $passengers = explode(';', $passengerDataString);
        
            // Mengurai setiap data penumpang
            foreach ($passengers as $passenger) {
                $details = explode(',', $passenger);
                if (count($details) === 4) {
                    $passengerArray[] = [
                        'name' => $details[0],
                        'age' => $details[1],
                        'gender' => $details[2],
                        'nationality' => $details[3],
                    ];
                }
            }
    
            return response()->json([
                'fbo_booking_id' => $bookingData->fbo_booking_id,
                'fbo_adult' => $bookingData->fbo_adult,
                'fbo_child' => $bookingData->fbo_child,
                'fbo_infant' => $bookingData->fbo_infant,
                'fbo_adult_publish' => $bookingData->fbo_adult_publish,
                'fbo_child_publish' => $bookingData->fbo_child_publish,
                'fbo_infant_publish' => $bookingData->fbo_infant_publish,
                'fbo_total_publish' => $bookingData->fbo_total_publish,
                'fbo_discount' => $bookingData->fbo_discount,
                'fbo_price_cut' => $bookingData->fbo_price_cut,
                'fbo_total_discount' => $bookingData->fbo_total_discount,
                'fbo_total_nett' => $bookingData->fbo_total_nett,
                'fbo_end_total' => $bookingData->fbo_end_total,
                'fbo_profit' => $bookingData->fbo_profit,
                'fbo_refund' => $bookingData->fbo_refund,
                'fbo_transaction_id' => $bookingData->fbo_transaction_id,
                'trip' => [
                    'fastboat' => ['fb_name' => $bookingData->trip->fastboat->fb_name],
                    'departure_port' => $bookingData->trip->departure->prt_name_en,
                    'departure_island' => $bookingData->trip->departure->island->isd_name,
                    'departure_time' => Carbon::parse($bookingData->fbo_departure_time)->format('H:i'),
                    'arrival_port' => $bookingData->trip->arrival->prt_name_en,
                    'arrival_island' => $bookingData->trip->arrival->island->isd_name,
                    'arrival_time' => Carbon::parse($bookingData->fbo_arrival_time)->format('H:i'),
                    'passengers' => $passengerArray,
                ],
                'checkPoint' =>[
                    'fcp_address' => $bookingData->checkPoint->fcp_address,
                ]
            ]);
        }
}
