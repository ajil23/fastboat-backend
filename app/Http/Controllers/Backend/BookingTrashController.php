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
use Barryvdh\DomPDF\Facade\Pdf;

class BookingTrashController extends Controller
{
    public function index(Request $request)
    {
        // Initial query for fastboat orders
        $query = BookingData::orderBy('created_at', 'desc')
            ->whereIn('fbo_transaction_status', ['waiting', 'remove', 'cancel']);

        // Apply filters if available
        if ($request->filled('order_id')) {
            $query->whereHas('contact', function ($q) use ($request) {
                $q->where('ctc_order_id', 'like', '%' . $request->order_id . '%');
            });
        }

        if ($request->filled('booking_id')) {
            $query->where('fbo_booking_id', $request->booking_id);
        }

        if ($request->filled('contact_name')) {
            $query->whereHas('contact', function ($q) use ($request) {
                $q->where('ctc_name', 'like', '%' . $request->contact_name . '%');
            });
        }

        if ($request->filled('contact_email')) {
            $query->whereHas('contact', function ($q) use ($request) {
                $q->where('ctc_email', 'like', '%' . $request->contact_email . '%');
            });
        }

        // Search for passenger name in fbo_passenger
        if ($request->filled('passenger_name')) {
            $passengerNameInput = $request->passenger_name;
            $query->where(function ($q) use ($passengerNameInput) {
                $q->where('fbo_passenger', 'like', '%' . $passengerNameInput . '%')
                    ->orWhereRaw("SUBSTRING_INDEX(fbo_passenger, ';', 1) LIKE ?", ["%{$passengerNameInput}%"]);
            });
        }

        if ($request->filled('company')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('cpn_name', $request->company);
            });
        }

        if ($request->filled('departure')) {
            $query->whereHas('trip.departure', function ($q) use ($request) {
                $q->where('prt_name_en', $request->departure);
            });
        }

        if ($request->filled('arrival')) {
            $query->whereHas('trip.arrival', function ($q) use ($request) {
                $q->where('prt_name_en', $request->arrival);
            });
        }

        if ($request->filled('fbo_source')) {
            $query->where('fbo_source', $request->fbo_source);
        }

        if ($request->filled('fbo_transaction_status')) {
            $query->where('fbo_transaction_status', $request->fbo_transaction_status);
        }

        if ($request->filled('daterange')) {
            // Assuming daterange format is 'YYYY-MM-DD to YYYY-MM-DD'
            $dates = explode(' to ', $request->daterange);
            if (count($dates) == 2) {
                $query->whereBetween('fbo_trip_date', [$dates[0], $dates[1]]);
            }
        }

        // Cek jika range_type dan daterange ada dalam request
        if ($request->filled('range_type') && $request->filled('daterange')) {
            $dates = explode(' to ', $request->daterange);

            // Tentukan kolom berdasarkan range_type yang dipilih
            $column = $request->range_type === 'trip_date' ? 'fbo_trip_date' : 'created_at';

            if (count($dates) === 2) {
                // Jika dua tanggal, gunakan sebagai rentang tanggal
                $startDate = Carbon::parse($dates[0])->format('Y-m-d');
                $endDate = Carbon::parse($dates[1])->format('Y-m-d');
                $query->whereDate($column, '>=', $startDate)
                    ->whereDate($column, '<=', $endDate);
            } else {
                // Jika hanya satu tanggal, gunakan untuk mencari tanggal tersebut saja
                $singleDate = Carbon::parse($dates[0])->format('Y-m-d');
                $query->whereDate($column, $singleDate);
            }
        }

        // Check if the "Trip Updated" checkbox is checked
        if ($request->filled('trip_updated')) {
            $query->where('fbo_log', 'like', '%Update%'); // Mencari entri yang mengandung kata "Update"
        }        

        // Fetch the filtered data
        $bookingData = $query->get();

        // Fetch the unique values for dropdowns
        $companies = BookingData::whereIn('fbo_transaction_status', ['waiting', 'remove', 'cancel'])
            ->with('company')
            ->select('fbo_company')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return $item->company;
            })
            ->unique('cpn_name');
        // Fetch the unique departure ports
        $departurePorts = BookingData::whereIn('fbo_transaction_status', ['waiting', 'remove', 'cancel'])->with('trip.departure')
            ->select('fbo_trip_id')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return $item->trip->departure;
            })
            ->unique('prt_name_en');
        $arrivalPorts = BookingData::whereIn('fbo_transaction_status', ['waiting', 'remove', 'cancel'])->with('trip.arrival')
            ->select('fbo_trip_id')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return $item->trip->arrival;
            })
            ->unique('prt_name_en');

        // Filter untuk source
        $sources = BookingData::whereIn('fbo_transaction_status', ['waiting', 'remove', 'cancel'])
            ->select('fbo_source')
            ->distinct()
            ->get();

        // Fetch the payment method data
        $paymentMethod = MasterPaymentMethod::all();

        foreach ($bookingData as $data) {
            // Cek apakah kolom fbo_log mengandung kata 'update'
            $data->isUpdated = !empty($data->fbo_log) && strpos($data->fbo_log, 'Update') !== false;
        } 

        // Return view with data and unique dropdown options
        return view('booking.trash.index', compact('bookingData', 'paymentMethod', 'companies', 'departurePorts', 'arrivalPorts',  'sources'));
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
            // Cari bookingData berdasarkan fbo_id
            $bookingData = BookingData::find($request->fbo_id);
    
            if (!$bookingData) {
                return back()->withErrors(['error' => 'Booking data not found.']);
            }
    
            $orderId = $bookingData->fbo_order_id;
    
            // Ambil semua data yang memiliki fbo_order_id yang sama
            $allBookingData = BookingData::where('fbo_order_id', $orderId)->get();
    
            // Tentukan fbo_transaction_id berdasarkan fbo_payment_method
            $fbo_transaction_id = '';
            if ($request->fbo_payment_method == 'pak_anang') {
                $fbo_transaction_id = 'received by Mr. Anang';
            } elseif ($request->fbo_payment_method == 'pay_on_port') {
                $fbo_transaction_id = 'collect';
            } elseif ($request->fbo_payment_method == 'cash') {
                $fbo_transaction_id = 'received by ' . $request->input('fbo_transaction_id');
            } else {
                // Gunakan nilai dari input pengguna untuk metode lain
                $fbo_transaction_id = $request->input('fbo_transaction_id');
            }
    
            // Pengecekan data fbo_log
            $user = Auth::user()->name; // Pengecekan user
            $date = now()->format('d-M-Y H:i:s'); // Tanggal
            $logbefore = $bookingData->fbo_log ? $bookingData->fbo_log . ';' : '';
    
            foreach ($allBookingData as $data) {
                $data->fbo_payment_status = 'paid';
                $data->fbo_payment_method = $request->fbo_payment_method;
                $data->fbo_transaction_id = $fbo_transaction_id; // Menggunakan logika baru
                $data->fbo_transaction_status = 'accepted';
                $data->fbo_log = $logbefore . $user . ', Mark as accept, ' . $date;
                $data->save();
            }
    
            // Catat log untuk perubahan
            $count = FastboatLog::where('fbl_booking_id', $bookingData->fbo_booking_id)
                ->where('fbl_type', 'like', 'Update transaction status%')
                ->count();
    
            FastboatLog::create([
                'fbl_booking_id' => $bookingData->fbo_booking_id,
                'fbl_type' => 'Update transaction status ' . ($count + 1),
                'fbl_data_before' => 'transaction_status:waiting',
                'fbl_data_after' => 'transaction_status:accepted',
            ]);
    
            toast('Status Payment has been updated for all matching orders!', 'success');
    
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
            'note' => $bookingData->contact->ctc_note,
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

    public function viewTicket(Request $request, $ticketId)
    {
        $ticketType = $request->input('downloadTicket');
        $dataTicket = BookingData::with(['trip.fastboat', 'trip.fastboat.company', 'trip.departure', 'trip.arrival', 'contact', 'availability'])->findOrFail($ticketId);

        $passengers = explode(';', $dataTicket->fbo_passenger);
        $passengerArray = []; // Inisialisasi array penumpang

        // Mengurai setiap data penumpang
        foreach ($passengers as $passenger) {
            $details = explode(',', $passenger);
            if (count($details) === 4) {
                // Mengubah umur menjadi string sesuai kategori
                $age = (int) $details[1];
                if ($age > 13) {
                    $ageGroup = 'ADULT';
                } elseif ($age >= 3 && $age <= 12) {
                    $ageGroup = 'CHILD';
                } elseif ($age >= 0 && $age <= 2) {
                    $ageGroup = 'INFANT';
                } else {
                    $ageGroup = 'UNKNOWN'; // Jika umur tidak valid
                }

                $passengerArray[] = [
                    'name' => $details[0],
                    'age' => $ageGroup, // Menggunakan kategori umur
                    'gender' => $details[2],
                    'nationality' => $details[3],
                ];
            }
        }

        // Format trip date
        $tripDate = new \DateTime($dataTicket->fbo_trip_date);
        $formattedTripDate = $tripDate->format('l, d M Y');

        // Memformat waktu departur
        $time = $dataTicket->availability->fba_dept_time ?? $dataTicket->trip->fbt_dept_time;
        $timeDateTime = new \DateTime($time);
        $formattedTime = $timeDateTime->format('H:i');

        // Memformat waktu arrival
        $arrivaltime = $dataTicket->fbo_arrival_time;
        $arrivaltimeDateTime = new \DateTime($arrivaltime);
        $arrivalformattedTime = $arrivaltimeDateTime->format('H:i');

        // Memformat created_at
        $bookingDate = new \DateTime($dataTicket->created_at);
        $formattedBookingDate = $bookingDate->format('l, d M Y');

        function getImagePath($imagePath)
        {
            // Jika menggunakan asset()
            if (strpos($imagePath, 'assets/') === 0) {
                return public_path($imagePath);
            }

            // Handle path untuk cpn_logo
            // Karena di database mungkin hanya tersimpan nama filenya saja
            return storage_path('app/public/' . $imagePath);
        }


        $data = [
            'name' => $dataTicket->contact->ctc_name,
            'email' => $dataTicket->contact->ctc_email,
            'phone' => $dataTicket->contact->ctc_phone,
            'fbo_booking_id' => $dataTicket->fbo_booking_id,
            'fbo_payment_status' => $dataTicket->fbo_payment_status,
            'fbo_trip_date' => $formattedTripDate,
            'fbo_checkin_point_address' => $dataTicket->checkinPoint->fcp_address,
            'fbo_checkin_point_maps' => $dataTicket->checkinPoint->fcp_maps,
            'fbo_pickup' => $dataTicket->fbo_pickup,
            'fbo_specific_pickup' => $dataTicket->fbo_specific_pickup,
            'fbo_contact_pickup' => $dataTicket->fbo_contact_pickup,
            'fbo_dropoff' => $dataTicket->fbo_dropoff,
            'fbo_specific_dropoff' => $dataTicket->fbo_specific_dropoff,
            'fbo_contact_dropoff' => $dataTicket->fbo_contact_dropoff,
            'cpn_name' => $dataTicket->trip->fastboat->company->cpn_name,
            'cpn_email' => $dataTicket->trip->fastboat->company->cpn_email,
            'cpn_phone' => $dataTicket->trip->fastboat->company->cpn_phone,
            'cpn_logo' => base64_encode(file_get_contents(getImagePath($dataTicket->trip->fastboat->company->cpn_logo))),
            'departure_port' => $dataTicket->trip->departure->prt_name_en,
            'departure_island' => $dataTicket->trip->departure->island->isd_name,
            'departure_time' => $formattedTime,
            'arrival_port' => $dataTicket->trip->arrival->prt_name_en,
            'arrival_island' => $dataTicket->trip->arrival->island->isd_name,
            'arrival_time' => $arrivalformattedTime,
            'passengers' => $passengerArray,
            'logo_ticket' => base64_encode(file_get_contents(public_path('assets/images/logo-ticket.png'))),
            'created_at' => $formattedBookingDate,
        ];

        if ($ticketType == "gt") {
            $pdf = Pdf::loadView('ticket.gt', $data);
            return $pdf->stream('ticket.pdf');
        } elseif ($ticketType == "agen1") {
            return view('ticket.agen1');
        } else {
            return view('ticket.agen2');
        }
    }
}
