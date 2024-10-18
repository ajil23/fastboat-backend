<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\FastboatLog;
use App\Models\MasterPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingTrashController extends Controller
{
    public function index()
    {
        $bookingData = BookingData::orderBy('created_at', 'desc')
            ->whereIn('fbo_transaction_status', ['waiting', 'remove', 'cancel'])->get();
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
        ]);

        DB::beginTransaction();
        try {
            $bookingData = BookingData::find($request->fbo_id);

            // Pengecekan data fbo_log
            $before = $bookingData->fbo_log;
            if ($before != NULL) {
                $logbefore = $before . ';';
            } else {
                $logbefore = '';
            }
            $user = Auth::user()->name; // Pengecekan user
            $date = now()->format('d-M-Y H:i:s'); // Tanggal

            if ($bookingData) {
                $bookingData->fbo_payment_status = 'paid';
                $bookingData->fbo_payment_method = $request->fbo_payment_method;
                $bookingData->fbo_transaction_id = $request->fbo_transaction_id;
                $bookingData->fbo_transaction_status = 'accepted';
                $bookingData->save();

                $count = FastboatLog::where('fbl_booking_id', $bookingData->fbo_booking_id)
                    ->where('fbl_type', 'like', 'Update transaction status%')
                    ->count();

                FastboatLog::create([
                    'fbl_booking_id' => $bookingData->fbo_booking_id,
                    'fbl_type' => 'Update transaction status ' . ($count + 1),
                    'fbl_data_before' => 'transaction_status:waiting',
                    'fbl_data_after' => 'transaction_status:accepted',
                ]);

                // Simpan log ke kolom `fbo_log` pada tabel booking_data
                $bookingData->fbo_log = $logbefore . $user . ',' . 'Mark as accept'  . ',' . $date;
                toast('Status Payment as been updated!', 'success');
                $bookingData->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengubah status: ' . $e->getMessage()]);
        }

        return redirect()->back();
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

        // Mengambil dan memformat data log
        $logDataString = $bookingData->fbo_log;
        $logArray = [];

        $logs = explode(';', $logDataString);

        foreach ($logs as $log) {
            $logDetails = explode(',', $log);
            if (count($logDetails) === 3) {
                $logArray[] = array(
                    'user' => trim($logDetails[0]),
                    'activity' => trim($logDetails[1]),
                    'date' => trim($logDetails[2])
                );
            }
        }

        return response()->json([
            'fbo_booking_id' => $bookingData->fbo_booking_id,
            'fbo_trip_date' => $bookingData->fbo_trip_date,
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
            'checkPoint' => [
                'fcp_address' => $bookingData->checkPoint->fcp_address,
            ],
            'logs' => $logArray,
        ]);
    }

    public function updateStatus(Request $request, $fbo_id)
    {
        $bookingData = BookingData::findOrFail($fbo_id);  // Sesuaikan model dengan kebutuhan
        $bookingData->fbo_transaction_status = 'remove';
        $bookingData->save();

        toast('Status Transaction as been removed!', 'success');
        return response()->json(['success' => true]);
    }
}
