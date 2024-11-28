<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\CustomerMail;
use App\Mail\SupplierMail;
use App\Models\BookingData;
use App\Models\FastboatLog;
use App\Models\MasterPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Helpers;
use App\Models\Contact;
use App\Models\FastboatAvailability;
use App\Models\FastboatCheckinPoint;
use App\Models\FastboatShuttle;
use App\Models\FastboatShuttleArea;
use App\Models\MasterCurrency;
use App\Models\MasterNationality;

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

        // Fetch logs from fastboatlog table for matching booking_id
        $fastboatLogs = FastboatLog::where('fbl_booking_id', $bookingData->fbo_booking_id)
            ->get(['fbl_data_before', 'fbl_data_after', 'created_at']); // Include created_at

        // Convert logs to array for JSON response with filtering condition
        $fastboatLogArray = $fastboatLogs->map(function ($log) {
            $beforeDetails = explode('|', $log->fbl_data_before);
            $afterDetails = explode('|', $log->fbl_data_after);

            $parseDetails = function ($details) {
                $parsedData = [];
                foreach ($details as $detail) {
                    $pair = explode(':', $detail);
                    if (count($pair) === 2) {
                        $parsedData[trim($pair[0])] = trim($pair[1]);
                    }
                }
                return $parsedData;
            };

            $dataBefore = $parseDetails($beforeDetails);
            $dataAfter = $parseDetails($afterDetails);

            // Filter logs that contain company, trip, and total_price
            if (
                isset($dataBefore['company'], $dataBefore['trip'], $dataBefore['total_price']) ||
                isset($dataAfter['company'], $dataAfter['trip'], $dataAfter['total_price'])
            ) {
                return [
                    'data_before' => $dataBefore,
                    'data_after' => $dataAfter,
                    'created_at' => $log->created_at, // Include created_at in the response
                ];
            }

            return null;
        })->filter()->values(); // Filter out null values

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
            'fastboatLogs' => $fastboatLogArray, // Updated key to fastboatLogs
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

    public function whatsappReservation($fbo_id)
    {
        $booking = BookingData::with(['trip.fastboat', 'trip.fastboat.company', 'trip.departure', 'trip.arrival', 'contact', 'availability'])->findOrFail($fbo_id);

        $passengers = explode(';', $booking->fbo_passenger);
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
        $tripDate = new \DateTime($booking->fbo_trip_date);
        $formattedDate = $tripDate->format('l, d M Y');

        // Memformat waktu
        $time = $booking->availability->fba_dept_time ?? $booking->trip->fbt_dept_time;
        $timeDateTime = new \DateTime($time);
        $formattedTime = $timeDateTime->format('H:i');

        return response()->json([
            'fbo_booking_id' => $booking->fbo_booking_id,
            'company' => $booking->trip->fastboat->company->cpn_name,
            'departure_port' => $booking->trip->departure->prt_name_en,
            'arrival_port' => $booking->trip->arrival->prt_name_en,
            'fbo_trip_date' => $formattedDate,
            'time' => $formattedTime,
            'name' => $booking->contact->ctc_name,
            'email' => $booking->contact->ctc_email,
            'phone' => $booking->contact->ctc_phone,
            'passengers' => $passengerArray,
            'cpn_phone' => $booking->trip->fastboat->company->cpn_phone,
        ]);
    }

    public function cancelTransaction(Request $request)
    {
        // Find the booking
        $bookingData = BookingData::find($request->fbo_id);

        if ($bookingData) {
            // Handle the refund options
            if ($request->fbo_payment_method == 'full_refund') {
                // Full refund: fbo_refund = fbo_end_total, fbo_profit = 0
                $bookingData->fbo_refund = $bookingData->fbo_end_total;
                $bookingData->fbo_profit = 0;
            } elseif ($request->fbo_payment_method == 'partial_refund') {
                // Partial refund: set refund, calculate profit
                $partialRefund = $request->partial_refund_amount;
                $bookingData->fbo_refund = $partialRefund;
                $bookingData->fbo_profit = $bookingData->fbo_end_total - $partialRefund;
            } elseif ($request->fbo_payment_method == 'full_charge') {
                // Full charge: refund = 0, profit = end total
                $bookingData->fbo_refund = 0;
                $bookingData->fbo_profit = $bookingData->fbo_end_total;
            }

            // Update other fields (if necessary)
            $bookingData->fbo_transaction_status = 'cancel';  // Example status
            $bookingData->fbo_payment_status = 'Refund';  // Example status
            $bookingData->fbo_payment_method = $request->fbo_payment_method ?? $bookingData->fbo_payment_method;

            // Save changes to the database
            $bookingData->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Transaction canceled successfully.');
        }

        // Return with error if booking not found
        return redirect()->back()->with('error', 'Transaction not found.');
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

    public function edit(Request $request, $fbo_order_id)
    {
        $orderId = BookingData::where('fbo_order_id', $fbo_order_id)->first();

        if ($orderId) {
            // Mengecek apakah fbo_booking_id berakhiran 'X' atau 'Y'
            $lastCharacter = substr($orderId->fbo_booking_id, -1);

            if ($lastCharacter === 'Y') {
                $direction = 'roundtrip';
            } elseif ($lastCharacter === 'X') {
                $direction = 'oneway';
            }
        }

        // Mengambil data booking
        $bookingDataEdit = BookingData::with(['trip.fastboat', 'trip.fastboat.company', 'trip.departure', 'trip.arrival', 'contact', 'availability'])->findOrFail($orderId->fbo_id);

        // Mengambil data ketersediaan fastboat
        $availabilityQuery = FastboatAvailability::whereHas('trip', function ($query) use ($bookingDataEdit) {
            $query->whereHas('departure', function ($q) use ($bookingDataEdit) {
                $q->where('prt_name_en', $bookingDataEdit->trip->departure->prt_name_en);
            })
                ->whereHas('arrival', function ($q) use ($bookingDataEdit) {
                    $q->where('prt_name_en', $bookingDataEdit->trip->arrival->prt_name_en);
                })
                ->whereHas('fastboat', function ($q) use ($bookingDataEdit) {
                    $q->where('fb_name', $bookingDataEdit->trip->fastboat->fb_name);
                })
                ->where('fba_date', $bookingDataEdit->fbo_trip_date)
                ->where('fba_stock', '>', $bookingDataEdit->fbo_adult + $bookingDataEdit->fbo_child);
        });

        $availability = $availabilityQuery->get();
        $shuttleType = null;
        $shuttleOption = null;
        $pickupAreas = [];
        $dropoffAreas = [];

        if (!$availability->isEmpty()) {
            $trip = $availability->first()->trip;
            $shuttleTypeReturn = $trip->fbt_shuttle_type;
            $shuttleOptionReturn = $trip->fbt_shuttle_option;

            foreach ($availability as $avail) {
                if ($shuttleTypeReturn && in_array($shuttleTypeReturn, ['Private', 'Sharing'])) {
                    $trip_id = $avail->trip->fbt_id;
                    $Areas = FastboatShuttle::where('s_trip', $trip_id)->get();

                    if ($shuttleOptionReturn === 'pickup') {
                        // Untuk opsi pickup
                        $pickupAreas = $Areas->isNotEmpty() ? $Areas->map(function ($value) {
                            return [
                                'id' => $value->area->sa_id,
                                'name' => $value->area->sa_name,
                                'pickup_meeting_point' => $value->s_meeting_point ?? '',
                            ];
                        })->toArray() : [];

                        $dropoffAreas = FastboatShuttleArea::all()->map(function ($area) {
                            return [
                                'id' => $area->sa_id,
                                'name' => $area->sa_name,
                            ];
                        })->toArray();
                    } elseif ($shuttleOptionReturn === 'drop') {
                        // Untuk opsi drop
                        $dropoffAreas = $Areas->isNotEmpty() ? $Areas->map(function ($value) {
                            return [
                                'id' => $value->area->sa_id,
                                'name' => $value->area->sa_name,
                                'dropoff_meeting_point' => $value->s_meeting_point ?? '',
                            ];
                        })->toArray() : [];

                        $pickupAreas = FastboatShuttleArea::all()->map(function ($area) {
                            return [
                                'id' => $area->sa_id,
                                'name' => $area->sa_name,
                            ];
                        })->toArray();
                    }

                    // Jika tidak ada data pickupAreas atau dropoffAreas, fallback ke FastboatShuttleArea
                    if (empty($pickupAreas)) {
                        $pickupAreas = FastboatShuttleArea::all()->map(function ($area) {
                            return [
                                'id' => $area->sa_id,
                                'name' => $area->sa_name,
                            ];
                        })->toArray();
                    }

                    if (empty($dropoffAreas)) {
                        $dropoffAreas = FastboatShuttleArea::all()->map(function ($area) {
                            return [
                                'id' => $area->sa_id,
                                'name' => $area->sa_name,
                            ];
                        })->toArray();
                    }
                }
            }
        }

        // Memformat waktu dan tanggal
        $time = $bookingDataEdit->availability->fba_dept_time ?? $bookingDataEdit->trip->fbt_dept_time;
        $departureformattedTime = (new \DateTime($time))->format('H:i');

        $arrivaltime = $bookingDataEdit->fbo_arrival_time;
        $arrivalformattedTime = (new \DateTime($arrivaltime))->format('H:i');

        $tripDate = new \DateTime($bookingDataEdit->fbo_trip_date);
        $formattedTripDate = $tripDate->format('l, d M Y');

        // Mengambil data penumpang
        $passengerArray = [];
        foreach (explode(';', $bookingDataEdit->fbo_passenger) as $passenger) {
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

        // Data untuk view
        $data = [
            'fbo_id' => $bookingDataEdit->fbo_id,
            'fbo_booking_id' => $bookingDataEdit->fbo_booking_id,
            'fbo_currency' => $bookingDataEdit->fbo_currency,
            'fbo_adult_nett' => $bookingDataEdit->fbo_adult_nett,
            'fbo_adult_publish' => $bookingDataEdit->fbo_adult_publish,
            'fbo_adult_currency' => $bookingDataEdit->fbo_adult_currency,
            'fbo_child_nett' => $bookingDataEdit->fbo_child_nett,
            'fbo_child_publish' => $bookingDataEdit->fbo_child_publish,
            'fbo_child_currency' => $bookingDataEdit->fbo_child_currency,
            'fbo_total_publish' => $bookingDataEdit->fbo_total_publish,
            'fbo_total_currency' => $bookingDataEdit->fbo_total_currency,
            'fbo_total_nett' => $bookingDataEdit->fbo_total_nett,
            'fbo_kurs' => $bookingDataEdit->fbo_kurs,
            'fbo_discount' => $bookingDataEdit->fbo_discount,
            'fbo_discount_total' => $bookingDataEdit->fbo_discount_total,
            'fbo_price_cut' => $bookingDataEdit->fbo_price_cut,
            'fbo_end_total' => $bookingDataEdit->fbo_end_total,
            'fbo_end_total_currency' => $bookingDataEdit->fbo_end_total_currency,
            'departure_port' => $bookingDataEdit->trip->departure->prt_name_en,
            'departure_island' => $bookingDataEdit->trip->departure->island->isd_name,
            'departure_time' => $departureformattedTime,
            'arrival_port' => $bookingDataEdit->trip->arrival->prt_name_en,
            'arrival_island' => $bookingDataEdit->trip->arrival->island->isd_name,
            'arrival_time' => $arrivalformattedTime,
            'fbo_trip_date' => $formattedTripDate,
            'passengers' => $passengerArray,
            'adult' => $bookingDataEdit->fbo_adult,
            'child' => $bookingDataEdit->fbo_child,
            'infant' => $bookingDataEdit->fbo_infant,
            'name' => $bookingDataEdit->contact->ctc_name,
            'email' => $bookingDataEdit->contact->ctc_email,
            'phone' => $bookingDataEdit->contact->ctc_phone,
            'nationality_name' => $bookingDataEdit->contact->nationality->nas_country,
            'nationality_id' => $bookingDataEdit->contact->nationality->nas_id,
            'note' => $bookingDataEdit->contact->ctc_note,
            'paymentMethod_name' => $bookingDataEdit->paymentMethod->py_name ?? $bookingDataEdit->fbo_payment_method,
            'paymentMethod_value' => $bookingDataEdit->paymentMethod->py_value ?? '',
            'transaction_id' => $bookingDataEdit->fbo_transaction_id,
            'pickupAreas' => $pickupAreas,
            'fbo_pickup' => $bookingDataEdit->fbo_pickup,
            'fbo_contact_pickup' => $bookingDataEdit->fbo_contact_pickup,
            'fbo_specific_pickup' => $bookingDataEdit->fbo_specific_pickup,
            'dropoffAreas' => $dropoffAreas,
            'fbo_dropoff' => $bookingDataEdit->fbo_dropoff,
            'fbo_contact_dropoff' => $bookingDataEdit->fbo_contact_dropoff,
            'fbo_specific_dropoff' => $bookingDataEdit->fbo_specific_dropoff,
            'fb_name' => $bookingDataEdit->trip->fastboat->fb_name,
        ];

        // Data tambahan untuk nationality dan payment
        $nationality = MasterNationality::all();
        $payment = MasterPaymentMethod::all();
        return view('booking.trash.edit', compact('data', 'nationality', 'payment', 'availability', 'direction'));
    }

    public function searchTrip(Request $request)
    {
        try {
            $tripDate = $request->input('fbo_trip_date');
            $departurePort = $request->input('fbo_departure_port');
            $arrivalPort = $request->input('fbo_arrival_port');
            $timeDept = $request->input('fbo_departure_time');
            $fbo_id = $request->input('fbo_id');
            $availability_id = $request->input('availability_id'); // ID for selected detail

            // Query to fetch available trips based on input criteria
            $availabilityQuery = FastboatAvailability::whereHas('trip.departure', function ($query) use ($departurePort) {
                $query->where('prt_name_en', $departurePort);
            })
                ->whereHas('trip.arrival', function ($query) use ($arrivalPort) {
                    $query->where('prt_name_en', $arrivalPort);
                })
                ->where('fba_date', $tripDate);

            // Filter by departure time if provided
            if ($timeDept) {
                $availabilityQuery->where(function ($query) use ($timeDept, $arrivalPort) {
                    $query->where('fba_dept_time', $timeDept)
                        ->orWhereHas('trip', function ($query) use ($timeDept, $arrivalPort) {
                            $query->where('fbt_dept_time', $timeDept)
                                ->whereHas('arrival', function ($query) use ($arrivalPort) {
                                    $query->where('prt_name_en', $arrivalPort);
                                });
                        });
                });
            }

            $availability = $availabilityQuery->get();

            // Get booking data and currency information for pricing calculations
            $bookingData = BookingData::where('fbo_id', $fbo_id)->first();
            $adultCount = $bookingData->fbo_adult ?? 1;
            $childCount = $bookingData->fbo_child ?? 0;
            $currency = MasterCurrency::where('cy_code', $bookingData->fbo_currency)->first();
            $kurs = $currency->cy_rate;

            // Map through availability to structure results
            $results = $availability->map(function ($item) use ($adultCount, $childCount, $kurs, $currency, $bookingData) {
                $priceAdult = (float) $item->fba_adult_publish ?? 0;
                $priceChild = (float) $item->fba_child_publish ?? 0;

                $totalPrice = ($priceAdult * $adultCount) + ($priceChild * $childCount);
                $totalPriceCurrency = $totalPrice / $kurs;
                if ($currency !== 'IDR') {
                    // Round up if the currency is not IDR
                    $totalPriceCurrency = ceil($totalPriceCurrency);
                }

                $total_end_before = $bookingData->fbo_end_total;

                // Calculate price cut
                $priceCut = $total_end_before - $totalPrice;

                return [
                    'availability_id' => $item->fba_id, // ID for selected detail
                    'fastboat_result' => $item->trip->fastboat->fb_id,
                    'departure_result' => $item->trip->departure->prt_id,
                    'arrival_result' => $item->trip->arrival->prt_id,
                    'fastboat_name' => $item->trip->fastboat->fb_name,
                    'departure_port' => $item->trip->departure->prt_name_en,
                    'arrival_port' => $item->trip->arrival->prt_name_en,
                    'departure_time' => $item->fba_time_dept ?? $item->trip->fbt_dept_time,
                    'arrival_time' => $item->trip->fbt_arrival_time,
                    'price_adult' => $priceAdult,
                    'price_child' => $priceChild,
                    'price_adult_nett' => $item->fba_adult_nett,
                    'price_child_nett' => $item->fba_child_nett,
                    'price_discount' => $item->fba_discount,
                    'total_price' => $totalPrice,
                    'total_price_currency' => $totalPriceCurrency,
                    'currency_code' => $currency->cy_code,
                    'total_end_before' => $total_end_before,
                    'price_cut' => $priceCut,
                ];
            });

            // Find and customize the selected detail based on availability_id
            $selectedDetail = $availability->where('id', $availability_id)->first();

            if ($selectedDetail) {
                $selected = [
                    'fastboat_name' => $selectedDetail->trip->fastboat->fb_name,
                    'departure_port' => $selectedDetail->trip->departure->prt_code,
                    'arrival_port' => $selectedDetail->trip->arrival->prt_code,
                    'departure_time' => $selectedDetail->fba_time_dept ?? $selectedDetail->trip->fbt_dept_time,
                    'arrival_time' => $selectedDetail->trip->fbt_arrival_time,
                ];
            } else {
                $selected = null;
            }

            if ($availability->isEmpty()) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            return response()->json([
                'data' => $results,
                'selected' => $selected,
                'adultCount' => $adultCount,
                'childCount' => $childCount,
                'kurs' => $kurs,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getFiltered(Request $request)
    {
        try {
            $tripDate = $request->input('fbo_trip_date');
            $departurePort = $request->input('fbo_departure_port');
            $arrivalPort = $request->input('fbo_arrival_port');

            $query = FastboatAvailability::where('fba_date', $tripDate);

            if ($departurePort) {
                $query->whereHas('trip.departure', function ($q) use ($departurePort) {
                    $q->where('prt_name_en', $departurePort);
                });
            }

            if ($arrivalPort) {
                $query->whereHas('trip.arrival', function ($q) use ($arrivalPort) {
                    $q->where('prt_name_en', $arrivalPort);
                });
            }

            $availabilities = $query->get();

            if ($availabilities->isEmpty()) {
                return response()->json([
                    'fbo_departure_ports' => [],
                    'fbo_arrival_ports' => [],
                    'fbo_departure_times' => [],
                ]);
            }

            $departurePorts = [];
            $arrivalPorts = [];
            $timeDepts = [];

            foreach ($availabilities as $availability) {
                $trip = $availability->trip;
                if (!in_array($trip->departure->prt_name_en, $departurePorts)) {
                    $departurePorts[] = $trip->departure->prt_name_en;
                }
                if (!in_array($trip->arrival->prt_name_en, $arrivalPorts)) {
                    $arrivalPorts[] = $trip->arrival->prt_name_en;
                }
                $deptTime = $availability->fba_dept_time ?? $trip->fbt_dept_time;
                $formattedTimeDept = date('H:i', strtotime($deptTime));
                if (!in_array($formattedTimeDept, $timeDepts)) {
                    $timeDepts[] = $formattedTimeDept;
                }
            }

            return response()->json([
                'fbo_departure_ports' => $departurePorts,
                'fbo_arrival_ports' => $arrivalPorts,
                'fbo_departure_times' => $timeDepts,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function searchTripReturn(Request $request)
    {
        try {
            $tripDateReturn = $request->input('fbo_trip_date_return');
            $departurePortReturn = $request->input('fbo_departure_port_return');
            $arrivalPortReturn = $request->input('fbo_arrival_port_return');
            $timeDeptReturn = $request->input('fbo_departure_time_return');
            $fbo_id = $request->input('fbo_id_return');
            $availability_id = $request->input('availability_id_return'); // ID untuk detail yang dipilih

            // Query FastboatAvailability berdasarkan filter trip
            $availabilityQuery = FastboatAvailability::whereHas('trip.departure', function ($query) use ($departurePortReturn) {
                $query->where('prt_name_en', $departurePortReturn);
            })
                ->whereHas('trip.arrival', function ($query) use ($arrivalPortReturn) {
                    $query->where('prt_name_en', $arrivalPortReturn);
                })
                ->where('fba_date', $tripDateReturn);

            // Filter waktu keberangkatan jika ada
            if ($timeDeptReturn) {
                $availabilityQuery->where(function ($query) use ($timeDeptReturn, $arrivalPortReturn) {
                    $query->where('fba_dept_time', $timeDeptReturn)
                        ->orWhereHas('trip', function ($query) use ($timeDeptReturn, $arrivalPortReturn) {
                            $query->where('fbt_dept_time', $timeDeptReturn)
                                ->whereHas('arrival', function ($query) use ($arrivalPortReturn) {
                                    $query->where('prt_name_en', $arrivalPortReturn);
                                });
                        });
                });
            }

            $availability = $availabilityQuery->get();

            // Mendapatkan data booking untuk jumlah dewasa dan anak
            $bookingData = BookingData::where('fbo_id', $fbo_id)->first();
            $adultCountReturn = $bookingData->fbo_adult ?? 1;
            $childCountReturn = $bookingData->fbo_child ?? 0;
            $currency = MasterCurrency::where('cy_code', $bookingData->fbo_currency)->first();
            $kurs = $currency->cy_rate;

            // Struktur hasil untuk FastboatAvailability dan detail BookingData
            $results = $availability->map(function ($item) use ($adultCountReturn, $childCountReturn, $kurs, $currency, $bookingData) {
                $priceAdultReturn = (float) $item->fba_adult_publish ?? 0;
                $priceChildReturn = (float) $item->fba_child_publish ?? 0;
                $totalPrice = ($priceAdultReturn * $adultCountReturn) + ($priceChildReturn * $childCountReturn);
                $totalPriceCurrency = $totalPrice / $kurs;
                if ($currency !== 'IDR') {
                    // Round up if the currency is not IDR
                    $totalPriceCurrency = ceil($totalPriceCurrency);
                }

                $total_end_before = $bookingData->fbo_end_total;

                $priceCut = $total_end_before - $totalPrice;

                return [
                    'availability_id' => $item->fba_id, // ID untuk detail yang dipilih
                    'fastboat_name' => $item->trip->fastboat->fb_name,
                    'departure_port' => $item->trip->departure->prt_code,
                    'arrival_port' => $item->trip->arrival->prt_code,
                    'departure_time' => $item->fba_time_dept ?? $item->trip->fbt_dept_time,
                    'arrival_time' => $item->trip->fbt_arrival_time,
                    'price_adult' => $priceAdultReturn,
                    'price_child' => $priceChildReturn,
                    'price_adult_nett' => $item->fba_adult_nett,
                    'price_child_nett' => $item->fba_child_nett,
                    'price_discount' => $item->fba_discount,
                    'total_price' => $totalPrice,
                    'total_price_currency' => $totalPriceCurrency,
                    'currency_code' => $currency->cy_code,
                    'total_end_before' => $total_end_before,
                    'price_cut' => $priceCut,
                ];
            });

            // Menemukan detail yang dipilih berdasarkan availability_id
            $selectedDetail = $availability->where('fba_id', $availability_id)->first();

            if ($selectedDetail) {
                $selected = [
                    'fastboat_name' => $selectedDetail->trip->fastboat->fb_name,
                    'departure_port' => $selectedDetail->trip->departure->prt_code,
                    'arrival_port' => $selectedDetail->trip->arrival->prt_code,
                    'departure_time' => $selectedDetail->fba_time_dept ?? $selectedDetail->trip->fbt_dept_time,
                    'arrival_time' => $selectedDetail->trip->fbt_arrival_time,
                ];
            } else {
                $selected = null;
            }

            if ($availability->isEmpty()) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            return response()->json([
                'data' => $results,
                'selected' => $selected,
                'adultCountReturn' => $adultCountReturn,
                'childCountReturn' => $childCountReturn,
                'kursReturn' => $kurs
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getFilteredReturn(Request $request)
    {
        try {
            $tripDateReturn = $request->input('fbo_trip_date_return');
            $departurePortReturn = $request->input('fbo_departure_port_return');
            $arrivalPortReturn = $request->input('fbo_arrival_port_return');

            $query = FastboatAvailability::where('fba_date', $tripDateReturn);

            if ($departurePortReturn) {
                $query->whereHas('trip.departure', function ($q) use ($departurePortReturn) {
                    $q->where('prt_name_en', $departurePortReturn);
                });
            }

            if ($arrivalPortReturn) {
                $query->whereHas('trip.arrival', function ($q) use ($arrivalPortReturn) {
                    $q->where('prt_name_en', $arrivalPortReturn);
                });
            }

            $availabilities = $query->get();

            if ($availabilities->isEmpty()) {
                return response()->json([
                    'fbo_departure_ports_return' => [],
                    'fbo_arrival_ports_return' => [],
                    'fbo_departure_times_return' => [],
                ]);
            }

            $departurePortReturns = [];
            $arrivalPortReturns = [];
            $timeDeptReturns = [];

            foreach ($availabilities as $availability) {
                $trip = $availability->trip;
                if (!in_array($trip->departure->prt_name_en, $departurePortReturns)) {
                    $departurePortReturns[] = $trip->departure->prt_name_en;
                }
                if (!in_array($trip->arrival->prt_name_en, $arrivalPortReturns)) {
                    $arrivalPortReturns[] = $trip->arrival->prt_name_en;
                }
                $deptTime = $availability->fba_dept_time ?? $trip->fbt_dept_time;
                $formattedTimeDept = date('H:i', strtotime($deptTime));
                if (!in_array($formattedTimeDept, $timeDeptReturns)) {
                    $timeDeptReturns[] = $formattedTimeDept;
                }
            }

            return response()->json([
                'fbo_departure_ports_return' => $departurePortReturns,
                'fbo_arrival_ports_return' => $arrivalPortReturns,
                'fbo_departure_times_return' => $timeDeptReturns,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function update(Request $request, $fbo_id)
    {
        $bookingDataEdit = BookingData::find($fbo_id);

        // Pengecekan data fbo_log
        $before = $bookingDataEdit->fbo_log;
        if ($before != NULL) {
            $logbefore = $before . ';';
        } else {
            $logbefore = '';
        }
        $user = Auth::user()->name; // Pengecekan user
        $date = now()->format('d-M-Y H:i:s'); // Tanggal 

        $activeTab = $request->input('active_tab');

        switch ($activeTab) {
            case 'trip':
                DB::beginTransaction();
                try {
                    // Mengambil data sebelum perubahan untuk log
                    $companyBefore = $bookingDataEdit->trip->fastboat->company->cpn_name;
                    $tripDateBefore = $bookingDataEdit->fbo_trip_date;
                    $departureIslandBefore = $bookingDataEdit->trip->departure->island->isd_name;
                    $departurePortBefore = $bookingDataEdit->trip->departure->prt_name_en;
                    $arrivalIslandBefore = $bookingDataEdit->trip->arrival->island->isd_name;
                    $arrivalPortBefore = $bookingDataEdit->trip->arrival->prt_name_en;
                    $kursBefore = $bookingDataEdit->fbo_kurs;
                    $adultBefore = $bookingDataEdit->fbo_adult;
                    $childBefore = $bookingDataEdit->fbo_child;
                    $totalNettBefore = $bookingDataEdit->fbo_total_nett;
                    $endTotalBefore = $bookingDataEdit->fbo_end_total;
                    $passenger = $bookingDataEdit->fbo_adult + $bookingDataEdit->fbo_child;

                    // Update departure data untuk oneway atau roundtrip
                    $orderId = $bookingDataEdit->fbo_order_id;
                    $orderData = BookingData::where('fbo_order_id', $orderId)->first();
                    $lastCharacter = substr($orderData->fbo_booking_id, -1);

                    if ($lastCharacter === 'X') {
                        $direction = 'oneway';
                    } elseif ($lastCharacter === 'Y' || $lastCharacter === 'Z') {
                        $direction = 'roundtrip';
                    } else {
                        $direction = 'oneway';
                    }

                    if ($direction === 'oneway') {
                        // Mengambil data untuk departure
                        $availabilityId = FastboatAvailability::find($request->availability_id);
                        $checkin = FastboatCheckinPoint::where('fcp_company', $availabilityId->trip->fastboat->company->cpn_id)->first();

                        $availabilityIdAfter = $request->availability_id;
                        $tripIdAfter = $availabilityId->trip->fbt_id;
                        $tripDateAfter = $availabilityId->fba_date;
                        $adultNettAfter = $availabilityId->fba_adult_nett;
                        $childNettAfter = $availabilityId->fba_child_nett;
                        $totalNettAfter = ($adultNettAfter * $adultBefore) + ($childNettAfter * $childBefore);
                        $adultPublishAfter = $availabilityId->fba_adult_publish;
                        $childPublishAfter = $availabilityId->fba_child_publish;
                        $totalPublishAfter = ($adultPublishAfter * $adultBefore) + ($childPublishAfter * $childBefore);
                        $adultCurrencyAfter = round($request->price_adult / $kursBefore);
                        $childCurrencyAfter = round($request->price_child / $kursBefore);
                        $totalCurrencyAfter = ($adultCurrencyAfter * $adultBefore) + ($childCurrencyAfter * $childBefore);
                        $discountAfter = $availabilityId->fba_discount;

                        // Tentukan price cut untuk departure
                        if ($adultCurrencyAfter > $adultPublishAfter) {
                            $priceCutAfter = (($childPublishAfter - $request->price_child) * $childBefore);
                        } elseif ($childCurrencyAfter > $childPublishAfter) {
                            $priceCutAfter = (($adultPublishAfter - $request->price_adult) * $adultBefore);
                        } else {
                            $priceCutAfter = (($adultPublishAfter - $request->price_adult) * $adultBefore) + (($childPublishAfter - $request->price_child) * $childBefore);
                        }

                        $discountTotalAfter = $discountAfter + $priceCutAfter;
                        $endTotalAfter = $request->fbo_end_total;
                        $endTotalCurrencyAfter = $request->fbo_end_total_currency;
                        $profitAfter = $endTotalAfter - $totalNettAfter;
                        $refundAfter = $endTotalBefore - $endTotalAfter;
                        $companyAfter = $availabilityId->trip->fastboat->company->cpn_name;
                        $departureIslandAfter = $availabilityId->trip->departure->island->isd_name;
                        $departurePortAfter = $availabilityId->trip->departure->prt_name_en;
                        $departureTimeAfter = $availabilityId->fba_dept_time ?? $availabilityId->trip->fbt_dept_time;
                        $arrivalIslandAfter = $availabilityId->trip->arrival->island->isd_name;
                        $arrivalPortAfter = $availabilityId->trip->arrival->prt_name_en;
                        $arrivalTimeAfter = $availabilityId->fba_arrival_time ?? $availabilityId->trip->fbt_arrival_time;
                        $chekinPointAfter = $checkin->fcp_id;
                        $updatedByAfter = $user;

                        $trip = $bookingDataEdit;
                        $trip->update([
                            'fbo_availability_id' => $availabilityIdAfter,
                            'fbo_trip_date' => $tripDateAfter,
                            'fbo_adult_nett' => $adultNettAfter,
                            'fbo_child_nett' => $childNettAfter,
                            'fbo_total_nett' => $totalNettAfter,
                            'fbo_adult_publish' => $adultPublishAfter,
                            'fbo_child_publish' => $childPublishAfter,
                            'fbo_total_publish' => $totalPublishAfter,
                            'fbo_adult_currency' => $adultCurrencyAfter,
                            'fbo_child_currency' => $childCurrencyAfter,
                            'fbo_total_currency' => $totalCurrencyAfter,
                            'fbo_discount' => $discountAfter,
                            'fbo_price_cut' => $priceCutAfter,
                            'fbo_discount_total' => $discountTotalAfter,
                            'fbo_end_total' => $endTotalAfter,
                            'fbo_end_total_currency' => $endTotalCurrencyAfter,
                            'fbo_profit' => $profitAfter,
                            'fbo_refund' => $refundAfter,
                            'fbo_departure_time' => $departureTimeAfter,
                            'fbo_arrival_time' => $arrivalTimeAfter,
                            'fbo_checkin_point' => $chekinPointAfter,
                            'fbo_updated_by' => $updatedByAfter,
                        ]);

                        // Mengembalikan stok pada availability sebelumnya
                        $stokBefore = FastboatAvailability::where('fba_id', $availabilityId)->lockForUpdate()->first();
                        $stokBefore->fba_stock += $passenger;
                        $stokBefore->save();

                        // Pengecekan ketersediaan stok sekaligus penanganan race condition
                        $stokAfter = FastboatAvailability::where('fba_id', $availabilityIdAfter)->lockForUpdate()->first();
                        if ($stokAfter->fba_stock < $passenger  + 1) {
                            return response()->json(['message' => 'Ticket stock is low'], 400);
                        }
                        $stokAfter->fba_stock -= $passenger;
                        $stokAfter->save();


                        $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                            ->where('fbl_type', 'like', 'Update Trip Data%')
                            ->count();

                        FastboatLog::create([
                            'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                            'fbl_type' => 'Update trip',
                            'fbl_data_before' => 'company:' . $companyBefore . '| trip:' . $departureIslandBefore . ' -> ' . $arrivalIslandBefore . '| total_price:' . $totalNettBefore . '| trip_date:' . $tripDateBefore,
                            'fbl_data_after' => 'company:' . $companyAfter . '| trip:' . $departureIslandAfter . ' -> ' . $arrivalIslandAfter . '| total_price:' . $totalNettAfter . '| trip_date:' . $tripDateAfter,
                        ]);

                        $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update trip' . ',' . $date;
                        $bookingDataEdit->save();
                    }

                    if ($direction === 'roundtrip') {
                        // Kondisi jika hanya mengupdate departure
                        if ($request->has('availability_id') && $request->input('availability_id') !== null && $request->input('availability_id_return') === null) {
                            $availabilityIdBefore = $bookingDataEdit->fbo_availability_id;
                            $availabilityId = FastboatAvailability::find($request->availability_id);
                            $checkin = FastboatCheckinPoint::where('fcp_company', $availabilityId->trip->fastboat->company->cpn_id)->first();

                            $availabilityIdAfter = $request->availability_id;
                            $tripIdAfter = $availabilityId->trip->fbt_id;
                            $tripDateAfter = $availabilityId->fba_date;
                            $adultNettAfter = $availabilityId->fba_adult_nett;
                            $childNettAfter = $availabilityId->fba_child_nett;
                            $totalNettAfter = ($adultNettAfter * $adultBefore) + ($childNettAfter * $childBefore);
                            $adultPublishAfter = $availabilityId->fba_adult_publish;
                            $childPublishAfter = $availabilityId->fba_child_publish;
                            $totalPublishAfter = ($adultPublishAfter * $adultBefore) + ($childPublishAfter * $childBefore);
                            $adultCurrencyAfter = round($request->price_adult / $kursBefore);
                            $childCurrencyAfter = round($request->price_child / $kursBefore);
                            $totalCurrencyAfter = ($adultCurrencyAfter * $adultBefore) + ($childCurrencyAfter * $childBefore);
                            $discountAfter = $availabilityId->fba_discount;

                            // Tentukan price cut untuk departure
                            if ($adultCurrencyAfter > $adultPublishAfter) {
                                $priceCutAfter = (($childPublishAfter - $request->price_child) * $childBefore);
                            } elseif ($childCurrencyAfter > $childPublishAfter) {
                                $priceCutAfter = (($adultPublishAfter - $request->price_adult) * $adultBefore);
                            } else {
                                $priceCutAfter = (($adultPublishAfter - $request->price_adult) * $adultBefore) + (($childPublishAfter - $request->price_child) * $childBefore);
                            }

                            $discountTotalAfter = $discountAfter + $priceCutAfter;
                            $endTotalAfter = $request->fbo_end_total;
                            $endTotalCurrencyAfter = $request->fbo_end_total_currency;
                            $profitAfter = $endTotalAfter - $totalNettAfter;
                            $refundAfter = $endTotalBefore - $endTotalAfter;
                            $companyAfter = $availabilityId->trip->fastboat->company->cpn_name;
                            $departureIslandAfter = $availabilityId->trip->departure->island->isd_name;
                            $departurePortAfter = $availabilityId->trip->departure->prt_name_en;
                            $departureTimeAfter = $availabilityId->fba_dept_time ?? $availabilityId->trip->fbt_dept_time;
                            $arrivalIslandAfter = $availabilityId->trip->arrival->island->isd_name;
                            $arrivalPortAfter = $availabilityId->trip->arrival->prt_name_en;
                            $arrivalTimeAfter = $availabilityId->fba_arrival_time ?? $availabilityId->trip->fbt_arrival_time;
                            $chekinPointAfter = $checkin->fcp_id;
                            $updatedByAfter = $user;

                            $trip = $bookingDataEdit;
                            $trip->update([
                                'fbo_availability_id' => $availabilityIdAfter,
                                'fbo_trip_date' => $tripDateAfter,
                                'fbo_adult_nett' => $adultNettAfter,
                                'fbo_child_nett' => $childNettAfter,
                                'fbo_total_nett' => $totalNettAfter,
                                'fbo_adult_publish' => $adultPublishAfter,
                                'fbo_child_publish' => $childPublishAfter,
                                'fbo_total_publish' => $totalPublishAfter,
                                'fbo_adult_currency' => $adultCurrencyAfter,
                                'fbo_child_currency' => $childCurrencyAfter,
                                'fbo_total_currency' => $totalCurrencyAfter,
                                'fbo_discount' => $discountAfter,
                                'fbo_price_cut' => $priceCutAfter,
                                'fbo_discount_total' => $discountTotalAfter,
                                'fbo_end_total' => $endTotalAfter,
                                'fbo_end_total_currency' => $endTotalCurrencyAfter,
                                'fbo_profit' => $profitAfter,
                                'fbo_refund' => $refundAfter,
                                'fbo_departure_time' => $departureTimeAfter,
                                'fbo_arrival_time' => $arrivalTimeAfter,
                                'fbo_checkin_point' => $chekinPointAfter,
                                'fbo_updated_by' => $updatedByAfter,
                            ]);

                            // Mengembalikan stok pada availability sebelumnya
                            $stokBeforeDepart = FastboatAvailability::where('fba_id', $availabilityIdBefore)->lockForUpdate()->first();
                            $stokBeforeDepart->fba_stock = (int) $stokBeforeDepart->fba_stock;
                            $stokBeforeDepart->fba_stock += (int)$passenger;
                            $stokBeforeDepart->save();

                            // Mengambil stok pada availability terbaru
                            $stokAfterDepart = FastboatAvailability::where('fba_id', $availabilityIdAfter)->lockForUpdate()->first();
                            $stokAfterDepart->fba_stock = (int) $stokAfterDepart->fba_stock;
                            $passenger = (int) $passenger;

                            // Mengecek ketersediaan stok
                            if ($stokAfterDepart->fba_stock < $passenger + 1) {
                                return response()->json(['message' => 'Ticket stock is low'], 400);
                            }

                            // Mengurangi stok
                            $stokAfterDepart->fba_stock -= $passenger;
                            $stokAfterDepart->save();

                            $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                                ->where('fbl_type', 'like', 'Update Trip Data%')
                                ->count();

                            FastboatLog::create([
                                'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                                'fbl_type' => 'Update trip',
                                'fbl_data_before' => 'company:' . $companyBefore . '| trip:' . $departureIslandBefore . ' -> ' . $arrivalIslandBefore . '| total_price:' . $totalNettBefore . '| trip_date:' . $tripDateBefore,
                                'fbl_data_after' => 'company:' . $companyAfter . '| trip:' . $departureIslandAfter . ' -> ' . $arrivalIslandAfter . '| total_price:' . $totalNettAfter . '| trip_date:' . $tripDateAfter,
                            ]);
                            $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update trip' . ',' . $date;
                            $bookingDataEdit->save();
                        }

                        // Kondisi jika hanya mengupdate return
                        if ($request->has('availability_id_return') && $request->input('availability_id_return') !== null && $request->input('availability_id') === null) {
                            $availabilityIdReturn = FastboatAvailability::find($request->availability_id_return);
                            $returnDepartureTime = $availabilityIdReturn->fba_dept_time ?? $availabilityIdReturn->trip->fbt_dept_time;
                            $returnArrivalTime = $availabilityIdReturn->fba_arrival_time ?? $availabilityIdReturn->trip->fbt_arrival_time;
                            $returnCheckin = FastboatCheckinPoint::where('fcp_company', $availabilityIdReturn->trip->fastboat->company->cpn_id)->first();

                            // Update return booking (Z)
                            $returnBookingData = BookingData::where('fbo_order_id', $orderId)
                                ->where('fbo_booking_id', 'like', '%Z')->first();

                            if ($returnBookingData) {
                                // Mengambil data sebelumnya (return)
                                $companyReturnBefore = $returnBookingData->trip->fastboat->company->cpn_name;
                                $tripDateReturnBefore = $returnBookingData->fbo_trip_date;
                                $departureIslandReturnBefore = $returnBookingData->trip->departure->island->isd_name;
                                $departurePortReturnBefore = $returnBookingData->trip->departure->prt_name_en;
                                $arrivalIslandReturnBefore = $returnBookingData->trip->arrival->island->isd_name;
                                $arrivalPortReturnBefore = $returnBookingData->trip->arrival->prt_name_en;
                                $kursReturnBefore = $returnBookingData->fbo_kurs;
                                $adultReturnBefore = $returnBookingData->fbo_adult;
                                $childReturnBefore = $returnBookingData->fbo_child;
                                $totalNettReturnBefore = $returnBookingData->fbo_total_nett;
                                $endTotalReturnBefore = $returnBookingData->fbo_end_total;
                                $availabilityReturnBefore = $returnBookingData->fbo_availability_id;

                                // Mengambil data sesudah (return)
                                $checkin = FastboatCheckinPoint::where('fcp_company', $availabilityIdReturn->trip->fastboat->company->cpn_id)->first();

                                $availabilityIdReturnAfter = $request->availability_id_return;
                                $tripIdReturnAfter = $availabilityIdReturn->trip->fbt_id;
                                $tripDateReturnAfter = $availabilityIdReturn->fba_date;
                                $adultNettReturnAfter = $availabilityIdReturn->fba_adult_nett;
                                $childNettReturnAfter = $availabilityIdReturn->fba_child_nett;
                                $totalNettReturnAfter = ($adultNettReturnAfter * $adultBefore) + ($childNettReturnAfter * $childBefore);
                                $adultPublishReturnAfter = $availabilityIdReturn->fba_adult_publish;
                                $childPublishReturnAfter = $availabilityIdReturn->fba_child_publish;
                                $totalPublishReturnAfter = ($adultPublishReturnAfter * $adultBefore) + ($childPublishReturnAfter * $childBefore);
                                $adultCurrencyReturnAfter = round($request->return_price_adult / $kursBefore);
                                $childCurrencyReturnAfter = round($request->return_price_child / $kursBefore);
                                $totalCurrencyReturnAfter = (round($request->return_price_adult / $kursBefore) * $adultBefore) + (round($request->return_price_child / $kursBefore) * $childBefore);
                                $discountReturnAfter = $availabilityIdReturn->fba_discount;

                                // Tentukan price cut
                                if ($adultCurrencyReturnAfter > $adultPublishReturnAfter) {
                                    $priceCutReturnAfter = (($childPublishReturnAfter - $request->return_price_child) * $childBefore);
                                } elseif ($childCurrencyReturnAfter > $childPublishReturnAfter) {
                                    $priceCutReturnAfter = (($adultPublishReturnAfter - $request->return_price_adult) * $adultBefore);
                                } else {
                                    $priceCutReturnAfter = (($adultPublishReturnAfter - $request->return_price_adult) * $adultBefore) + (($childPublishReturnAfter - $request->return_price_child) * $childBefore);
                                }

                                $discountTotalReturnAfter = $discountReturnAfter + $priceCutReturnAfter;
                                $endTotalReturnAfter = $request->return_fbo_end_total;
                                $endTotalCurrencyReturnAfter = $request->return_fbo_end_total_currency;
                                $profitReturnAfter = $endTotalReturnAfter - $totalNettReturnAfter;
                                $refundReturnAfter = $endTotalBefore - $endTotalReturnAfter;
                                $companyReturnAfter = $availabilityIdReturn->trip->fastboat->company->cpn_name;
                                $departureIslandReturnAfter = $availabilityIdReturn->trip->departure->island->isd_name;
                                $departurePortReturnAfter = $availabilityIdReturn->trip->departure->prt_name_en;
                                $departureTimeReturnAfter = $availabilityIdReturn->fba_dept_time ?? $availabilityIdReturn->trip->fbt_dept_time;
                                $arrivalIslandReturnAfter = $availabilityIdReturn->trip->arrival->island->isd_name;
                                $arrivalPortReturnAfter = $availabilityIdReturn->trip->arrival->prt_name_en;
                                $arrivalTimeReturnAfter = $availabilityIdReturn->fba_arrival_time ?? $availabilityIdReturn->trip->fbt_arrival_time;
                                $chekinPointReturnAfter = $checkin->fcp_id;
                                $updatedByReturnAfter = $user;

                                $returnBookingData->update([
                                    'fbo_availability_id' => $availabilityIdReturnAfter,
                                    'fbo_trip_date' => $tripDateReturnAfter,
                                    'fbo_adult_nett' => $adultNettReturnAfter,
                                    'fbo_child_nett' => $childNettReturnAfter,
                                    'fbo_total_nett' => $totalNettReturnAfter,
                                    'fbo_adult_publish' => $adultPublishReturnAfter,
                                    'fbo_child_publish' => $childPublishReturnAfter,
                                    'fbo_total_publish' => $totalPublishReturnAfter,
                                    'fbo_adult_currency' => $adultCurrencyReturnAfter,
                                    'fbo_child_currency' => $childCurrencyReturnAfter,
                                    'fbo_total_currency' => $totalCurrencyReturnAfter,
                                    'fbo_discount' => $discountReturnAfter,
                                    'fbo_price_cut' => $priceCutReturnAfter,
                                    'fbo_discount_total' => $discountTotalReturnAfter,
                                    'fbo_end_total' => $endTotalReturnAfter,
                                    'fbo_end_total_currency' => $endTotalCurrencyReturnAfter,
                                    'fbo_profit' => $profitReturnAfter,
                                    'fbo_refund' => $refundReturnAfter,
                                    'fbo_departure_time' => $departureTimeReturnAfter,
                                    'fbo_arrival_time' => $arrivalTimeReturnAfter,
                                    'fbo_checkin_point' => $chekinPointReturnAfter,
                                    'fbo_updated_by' => $updatedByReturnAfter,
                                ]);

                                // Mengembalikan stok pada availability sebelumnya
                                $stokBeforeReturn = FastboatAvailability::where('fba_id', $availabilityReturnBefore)->lockForUpdate()->first();
                                $stokBeforeReturn->fba_stock += $passenger;
                                $stokBeforeReturn->save();

                                // Pengecekan ketersediaan stok sekaligus penanganan race condition
                                $stokAfterReturn = FastboatAvailability::where('fba_id', $availabilityIdReturnAfter)->lockForUpdate()->first();
                                if ($stokAfterReturn->fba_stock < $passenger  + 1) {
                                    return response()->json(['message' => 'Ticket stock is low'], 400);
                                }
                                $stokAfterReturn->fba_stock -= $passenger;
                                $stokAfterReturn->save();

                                $count = FastboatLog::where('fbl_booking_id', $returnBookingData->fbo_booking_id)
                                    ->where('fbl_type', 'like', 'Update Trip Data%')
                                    ->count();

                                FastboatLog::create([
                                    'fbl_booking_id' => $returnBookingData->fbo_booking_id,
                                    'fbl_type' => 'Update trip',
                                    'fbl_data_before' => 'company:' . $companyReturnBefore . '| trip:' . $departureIslandReturnBefore . ' -> ' . $arrivalIslandReturnBefore . '| total_price:' . $totalNettReturnBefore . '| trip_date:' . $tripDateReturnBefore,
                                    'fbl_data_after' => 'company:' . $companyReturnAfter . '| trip:' . $departureIslandReturnAfter . ' -> ' . $arrivalIslandReturnAfter . '| total_price:' . $totalNettReturnAfter . '| trip_date:' . $tripDateReturnAfter,
                                ]);

                                $returnBookingData->fbo_log = $logbefore . $user . ',' . 'Update trip' . ',' . $date;
                                $returnBookingData->save();
                            }
                        }


                        // Jika keduanya ada, lakukan update roundtrip
                        if ($request->has('availability_id') && $request->input('availability_id') !== null && $request->has('availability_id_return') && $request->input('availability_id_return') !== null) {
                            $departureBookingData = BookingData::where('fbo_order_id', $orderId)
                                ->where('fbo_booking_id', 'like', '%Y')->first();
                            $availabilityIdBefore = $departureBookingData->fbo_availability_id;
                            $availabilityId = FastboatAvailability::find($request->availability_id);
                            $checkin = FastboatCheckinPoint::where('fcp_company', $availabilityId->trip->fastboat->company->cpn_id)->first();

                            $availabilityIdAfter = $request->availability_id;
                            $tripIdAfter = $availabilityId->trip->fbt_id;
                            $tripDateAfter = $availabilityId->fba_date;
                            $adultNettAfter = $availabilityId->fba_adult_nett;
                            $childNettAfter = $availabilityId->fba_child_nett;
                            $totalNettAfter = ($adultNettAfter * $adultBefore) + ($childNettAfter * $childBefore);
                            $adultPublishAfter = $availabilityId->fba_adult_publish;
                            $childPublishAfter = $availabilityId->fba_child_publish;
                            $totalPublishAfter = ($adultPublishAfter * $adultBefore) + ($childPublishAfter * $childBefore);
                            $adultCurrencyAfter = round($request->price_adult / $kursBefore);
                            $childCurrencyAfter = round($request->price_child / $kursBefore);
                            $totalCurrencyAfter = ($adultCurrencyAfter * $adultBefore) + ($childCurrencyAfter * $childBefore);
                            $discountAfter = $availabilityId->fba_discount;

                            // Tentukan price cut untuk departure
                            if ($adultCurrencyAfter > $adultPublishAfter) {
                                $priceCutAfter = (($childPublishAfter - $request->price_child) * $childBefore);
                            } elseif ($childCurrencyAfter > $childPublishAfter) {
                                $priceCutAfter = (($adultPublishAfter - $request->price_adult) * $adultBefore);
                            } else {
                                $priceCutAfter = (($adultPublishAfter - $request->price_adult) * $adultBefore) + (($childPublishAfter - $request->price_child) * $childBefore);
                            }

                            $discountTotalAfter = $discountAfter + $priceCutAfter;
                            $endTotalAfter = $request->fbo_end_total;
                            $endTotalCurrencyAfter = $request->fbo_end_total_currency;
                            $profitAfter = $endTotalAfter - $totalNettAfter;
                            $refundAfter = $endTotalBefore - $endTotalAfter;
                            $companyAfter = $availabilityId->trip->fastboat->company->cpn_name;
                            $departureIslandAfter = $availabilityId->trip->departure->island->isd_name;
                            $departurePortAfter = $availabilityId->trip->departure->prt_name_en;
                            $departureTimeAfter = $availabilityId->fba_dept_time ?? $availabilityId->trip->fbt_dept_time;
                            $arrivalIslandAfter = $availabilityId->trip->arrival->island->isd_name;
                            $arrivalPortAfter = $availabilityId->trip->arrival->prt_name_en;
                            $arrivalTimeAfter = $availabilityId->fba_arrival_time ?? $availabilityId->trip->fbt_arrival_time;
                            $chekinPointAfter = $checkin->fcp_id;
                            $updatedByAfter = $user;

                            if ($departureBookingData) {
                                $departureBookingData->update([
                                    'fbo_availability_id' => $availabilityIdAfter,
                                    'fbo_trip_date' => $tripDateAfter,
                                    'fbo_adult_nett' => $adultNettAfter,
                                    'fbo_child_nett' => $childNettAfter,
                                    'fbo_total_nett' => $totalNettAfter,
                                    'fbo_adult_publish' => $adultPublishAfter,
                                    'fbo_child_publish' => $childPublishAfter,
                                    'fbo_total_publish' => $totalPublishAfter,
                                    'fbo_adult_currency' => $adultCurrencyAfter,
                                    'fbo_child_currency' => $childCurrencyAfter,
                                    'fbo_total_currency' => $totalCurrencyAfter,
                                    'fbo_discount' => $discountAfter,
                                    'fbo_price_cut' => $priceCutAfter,
                                    'fbo_discount_total' => $discountTotalAfter,
                                    'fbo_end_total' => $endTotalAfter,
                                    'fbo_end_total_currency' => $endTotalCurrencyAfter,
                                    'fbo_profit' => $profitAfter,
                                    'fbo_refund' => $refundAfter,
                                    'fbo_departure_time' => $departureTimeAfter,
                                    'fbo_arrival_time' => $arrivalTimeAfter,
                                    'fbo_checkin_point' => $chekinPointAfter,
                                    'fbo_updated_by' => $updatedByAfter,
                                ]);

                                // Mengembalikan stok pada availability sebelumnya
                                $stokBeforeDepart = FastboatAvailability::where('fba_id', $availabilityIdBefore)->lockForUpdate()->first();
                                $stokBeforeDepart->fba_stock += $passenger;
                                $stokBeforeDepart->save();

                                // Pengecekan ketersediaan stok sekaligus penanganan race condition
                                $stokAfterDepart = FastboatAvailability::where('fba_id', $availabilityIdAfter)->lockForUpdate()->first();
                                if ($stokAfterDepart->fba_stock < $passenger  + 1) {
                                    return response()->json(['message' => 'Ticket stock is low'], 400);
                                }
                                $stokAfterDepart->fba_stock -= $passenger;
                                $stokAfterDepart->save();

                                $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                                    ->where('fbl_type', 'like', 'Update Trip Data%')
                                    ->count();

                                FastboatLog::create([
                                    'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                                    'fbl_type' => 'Update trip',
                                    'fbl_data_before' => 'company:' . $companyBefore . '| trip:' . $departureIslandBefore . ' -> ' . $arrivalIslandBefore . '| total_price:' . $totalNettBefore . '| trip_date:' . $tripDateBefore,
                                    'fbl_data_after' => 'company:' . $companyAfter . '| trip:' . $departureIslandAfter . ' -> ' . $arrivalIslandAfter . '| total_price:' . $totalNettAfter . '| trip_date:' . $tripDateAfter,
                                ]);

                                $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update trip' . ',' . $date;
                                $bookingDataEdit->save();
                            }

                            // Update return trip (Z)
                            if ($request->availability_id_return) {
                                $availabilityIdReturn = FastboatAvailability::find($request->availability_id_return);
                                $returnDepartureTime = $availabilityIdReturn->fba_dept_time ?? $availabilityIdReturn->trip->fbt_dept_time;
                                $returnArrivalTime = $availabilityIdReturn->fba_arrival_time ?? $availabilityIdReturn->trip->fbt_arrival_time;
                                $returnCheckin = FastboatCheckinPoint::where('fcp_company', $availabilityIdReturn->trip->fastboat->company->cpn_id)->first();

                                // Update return booking (Z)
                                $returnBookingData = BookingData::where('fbo_order_id', $orderId)
                                    ->where('fbo_booking_id', 'like', '%Z')->first();

                                if ($returnBookingData) {
                                    // Mengambil data sebelumnya (return)
                                    $companyReturnBefore = $returnBookingData->trip->fastboat->company->cpn_name;
                                    $tripDateReturnBefore = $returnBookingData->fbo_trip_date;
                                    $departureIslandReturnBefore = $returnBookingData->trip->departure->island->isd_name;
                                    $departurePortReturnBefore = $returnBookingData->trip->departure->prt_name_en;
                                    $arrivalIslandReturnBefore = $returnBookingData->trip->arrival->island->isd_name;
                                    $arrivalPortReturnBefore = $returnBookingData->trip->arrival->prt_name_en;
                                    $kursReturnBefore = $returnBookingData->fbo_kurs;
                                    $adultReturnBefore = $returnBookingData->fbo_adult;
                                    $childReturnBefore = $returnBookingData->fbo_child;
                                    $totalNettReturnBefore = $returnBookingData->fbo_total_nett;
                                    $endTotalReturnBefore = $returnBookingData->fbo_end_total;
                                    $availabilityIdReturnBefore = $returnBookingData->fbo_availability_id;

                                    // Mengambil data sesudah (return)
                                    $availabilityIdReturn = FastboatAvailability::find($request->availability_id_return);
                                    $checkin = FastboatCheckinPoint::where('fcp_company', $availabilityIdReturn->trip->fastboat->company->cpn_id)->first();

                                    $availabilityIdReturnAfter = $request->availability_id_return;
                                    $tripIdReturnAfter = $availabilityIdReturn->trip->fbt_id;
                                    $tripDateReturnAfter = $availabilityIdReturn->fba_date;
                                    $adultNettReturnAfter = $availabilityIdReturn->fba_adult_nett;
                                    $childNettReturnAfter = $availabilityIdReturn->fba_child_nett;
                                    $totalNettReturnAfter = ($adultNettReturnAfter * $adultBefore) + ($childNettReturnAfter * $childBefore);
                                    $adultPublishReturnAfter = $availabilityIdReturn->fba_adult_publish;
                                    $childPublishReturnAfter = $availabilityIdReturn->fba_child_publish;
                                    $totalPublishReturnAfter = ($adultPublishReturnAfter * $adultBefore) + ($childPublishReturnAfter * $childBefore);
                                    $adultCurrencyReturnAfter = round($request->return_price_adult / $kursBefore);
                                    $childCurrencyReturnAfter = round($request->return_price_child / $kursBefore);
                                    $totalCurrencyReturnAfter = (round($request->return_price_adult / $kursBefore) * $adultBefore) + (round($request->return_price_child / $kursBefore) * $childBefore);
                                    $discountReturnAfter = $availabilityIdReturn->fba_discount;

                                    // Tentukan price cut
                                    if ($adultCurrencyReturnAfter > $adultPublishReturnAfter) {
                                        $priceCutReturnAfter = (($childPublishReturnAfter - $request->return_price_child) * $childBefore);
                                    } elseif ($childCurrencyReturnAfter > $childPublishReturnAfter) {
                                        $priceCutReturnAfter = (($adultPublishReturnAfter - $request->return_price_adult) * $adultBefore);
                                    } else {
                                        $priceCutReturnAfter = (($adultPublishReturnAfter - $request->return_price_adult) * $adultBefore) + (($childPublishReturnAfter - $request->return_price_child) * $childBefore);
                                    }

                                    $discountTotalReturnAfter = $discountReturnAfter + $priceCutReturnAfter;
                                    $endTotalReturnAfter = $request->return_fbo_end_total;
                                    $endTotalCurrencyReturnAfter = $request->return_fbo_end_total_currency;
                                    $profitReturnAfter = $endTotalReturnAfter - $totalNettReturnAfter;
                                    $refundReturnAfter = $endTotalBefore - $endTotalReturnAfter;
                                    $companyReturnAfter = $availabilityIdReturn->trip->fastboat->company->cpn_name;
                                    $departureIslandReturnAfter = $availabilityIdReturn->trip->departure->island->isd_name;
                                    $departurePortReturnAfter = $availabilityIdReturn->trip->departure->prt_name_en;
                                    $departureTimeReturnAfter = $availabilityIdReturn->fba_dept_time ?? $availabilityIdReturn->trip->fbt_dept_time;
                                    $arrivalIslandReturnAfter = $availabilityIdReturn->trip->arrival->island->isd_name;
                                    $arrivalPortReturnAfter = $availabilityIdReturn->trip->arrival->prt_name_en;
                                    $arrivalTimeReturnAfter = $availabilityIdReturn->fba_arrival_time ?? $availabilityIdReturn->trip->fbt_arrival_time;
                                    $chekinPointReturnAfter = $checkin->fcp_id;
                                    $updatedByReturnAfter = $user;

                                    $returnBookingData->update([
                                        'fbo_availability_id' => $availabilityIdReturnAfter,
                                        'fbo_trip_date' => $tripDateReturnAfter,
                                        'fbo_adult_nett' => $adultNettReturnAfter,
                                        'fbo_child_nett' => $childNettReturnAfter,
                                        'fbo_total_nett' => $totalNettReturnAfter,
                                        'fbo_adult_publish' => $adultPublishReturnAfter,
                                        'fbo_child_publish' => $childPublishReturnAfter,
                                        'fbo_total_publish' => $totalPublishReturnAfter,
                                        'fbo_adult_currency' => $adultCurrencyReturnAfter,
                                        'fbo_child_currency' => $childCurrencyReturnAfter,
                                        'fbo_total_currency' => $totalCurrencyReturnAfter,
                                        'fbo_discount' => $discountReturnAfter,
                                        'fbo_price_cut' => $priceCutReturnAfter,
                                        'fbo_discount_total' => $discountTotalReturnAfter,
                                        'fbo_end_total' => $endTotalReturnAfter,
                                        'fbo_end_total_currency' => $endTotalCurrencyReturnAfter,
                                        'fbo_profit' => $profitReturnAfter,
                                        'fbo_refund' => $refundReturnAfter,
                                        'fbo_departure_time' => $departureTimeReturnAfter,
                                        'fbo_arrival_time' => $arrivalTimeReturnAfter,
                                        'fbo_checkin_point' => $chekinPointReturnAfter,
                                        'fbo_updated_by' => $updatedByReturnAfter,
                                    ]);

                                    // Mengembalikan stok pada availability sebelumnya
                                    $stokBeforeReturn = FastboatAvailability::where('fba_id', $availabilityIdReturnBefore)->lockForUpdate()->first();
                                    $stokBeforeReturn->fba_stock += $passenger;
                                    $stokBeforeReturn->save();

                                    // Pengecekan ketersediaan stok sekaligus penanganan race condition
                                    $stokAfterReturn = FastboatAvailability::where('fba_id', $availabilityIdReturnAfter)->lockForUpdate()->first();
                                    if ($stokAfterReturn->fba_stock < $passenger  + 1) {
                                        return response()->json(['message' => 'Ticket stock is low'], 400);
                                    }
                                    $stokAfterReturn->fba_stock -= $passenger;
                                    $stokAfterReturn->save();

                                    $count = FastboatLog::where('fbl_booking_id', $returnBookingData->fbo_booking_id)
                                        ->where('fbl_type', 'like', 'Update Trip Data%')
                                        ->count();

                                    FastboatLog::create([
                                        'fbl_booking_id' => $returnBookingData->fbo_booking_id,
                                        'fbl_type' => 'Update trip',
                                        'fbl_data_before' => 'company:' . $companyReturnBefore . '| trip:' . $departureIslandReturnBefore . ' -> ' . $arrivalIslandReturnBefore . '| total_price:' . $totalNettReturnBefore . '| trip_date:' . $tripDateReturnBefore,
                                        'fbl_data_after' => 'company:' . $companyReturnAfter . '| trip:' . $departureIslandReturnAfter . ' -> ' . $arrivalIslandReturnAfter . '| total_price:' . $totalNettReturnAfter . '| trip_date:' . $tripDateReturnAfter,
                                    ]);

                                    $returnBookingData->fbo_log = $logbefore . $user . ',' . 'Update trip' . ',' . $date;
                                    $returnBookingData->save();
                                }
                            }
                        }
                    }

                    // Commit transaksi
                    DB::commit();

                    toast('Trip data has been updated successfully!', 'success');
                    return redirect()->route('trash.view');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
                }
                break;

            case 'passenger':
                DB::beginTransaction();
                try {
                    // Mengambil data sebelum
                    $passengerBefore = $bookingDataEdit->fbo_passenger;

                    // Mengambil data sesudah
                    $passengerAfter = $request->fbo_passenger;

                    // Mengecek direction dan menentukan apakah akan update 1 atau 2 data
                    $orderId = $bookingDataEdit->fbo_order_id;
                    $orderData = BookingData::where('fbo_order_id', $orderId)->first();
                    $lastCharacter = substr($orderData->fbo_booking_id, -1); // Mengambil karakter terakhir dari booking ID

                    if ($lastCharacter === 'X') {
                        $direction = 'oneway';
                    } elseif ($lastCharacter === 'Y' || $lastCharacter === 'Z') {
                        $direction = 'roundtrip';
                    } else {
                        // Atur default jika tidak terdeteksi
                        $direction = 'oneway';
                    }

                    // Update data untuk passenger, menyesuaikan dengan direction
                    if ($direction === 'oneway') {
                        // Update hanya untuk satu data (BookingData pertama)
                        $passenger = $bookingDataEdit;
                        $passenger->update([
                            'fbo_passenger' => $passengerAfter,
                        ]);

                        // Buat log perubahan untuk passenger
                        $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                            ->where('fbl_type', 'like', 'Update Passenger Data%')
                            ->count();

                        FastboatLog::create([
                            'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                            'fbl_type' => 'Update Passenger Data ' . ($count + 1),
                            'fbl_data_before' => $passengerBefore,
                            'fbl_data_after' => $passengerAfter,
                        ]);
                    } elseif ($direction === 'roundtrip') {
                        // Update booking pertama (berakhiran Y)
                        $passenger1 = BookingData::where('fbo_order_id', $orderId)
                            ->where('fbo_booking_id', 'like', '%Y')->first(); // Booking ID yang berakhiran 'Y'

                        // Update booking kedua (berakhiran Z)
                        $passenger2 = BookingData::where('fbo_order_id', $orderId)
                            ->where('fbo_booking_id', 'like', '%Z')->first(); // Booking ID yang berakhiran 'Z'

                        if ($passenger1) {
                            $passenger1->update([
                                'fbo_passenger' => $passengerAfter,
                            ]);

                            // Log perubahan untuk passenger pertama (Y)
                            $countFirst = FastboatLog::where('fbl_booking_id', $passenger1->fbo_booking_id)
                                ->where('fbl_type', 'like', 'Update Passenger Data%')
                                ->count();

                            FastboatLog::create([
                                'fbl_booking_id' => $passenger1->fbo_booking_id,
                                'fbl_type' => 'Update Passenger Data ' . ($countFirst + 1),
                                'fbl_data_before' => $passengerBefore,
                                'fbl_data_after' => $passengerAfter,
                            ]);

                            // Update log untuk passenger pertama (Y)
                            $passenger1->fbo_log = $logbefore . $user . ',' . 'Update Passenger Data' . ',' . $date;
                            $passenger1->save();
                        }

                        if ($passenger2) {
                            $passenger2->update([
                                'fbo_passenger' => $passengerAfter,
                            ]);

                            // Log perubahan untuk passenger kedua (Z) - Return
                            $countSecond = FastboatLog::where('fbl_booking_id', $passenger2->fbo_booking_id)
                                ->where('fbl_type', 'like', 'Update Passenger Data%')
                                ->count();

                            FastboatLog::create([
                                'fbl_booking_id' => $passenger2->fbo_booking_id,
                                'fbl_type' => 'Update Passenger Data ' . ($countSecond + 1),
                                'fbl_data_before' => $passengerBefore,
                                'fbl_data_after' => $passengerAfter,
                            ]);

                            // Update log untuk passenger kedua (Z) (Return)
                            $passenger2->fbo_log = $logbefore . $user . ',' . 'Update Passenger Data' . ',' . $date;
                            $passenger2->save();
                        }
                    }

                    // Update log di bookingDataEdit untuk passenger pertama (Y)
                    $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update Passenger Data' . ',' . $date;
                    $bookingDataEdit->save();

                    // Commit Transaksi
                    DB::commit();

                    // Berikan notifikasi setelah commit
                    toast('Data booking has been updated successfully!', 'success');
                    return redirect()->route('trash.view');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
                }
                break;

            case 'shuttle':
                DB::beginTransaction();
                try {
                    // Mengambil data sebelum
                    $pickupBefore = $bookingDataEdit->fbo_pickup;
                    $specificPickupBefore = $bookingDataEdit->fbo_specific_pickup;
                    $contactPickupBefore = $bookingDataEdit->fbo_contact_pickup;
                    $dropoffBefore = $bookingDataEdit->fbo_dropoff;
                    $specificDropoffBefore = $bookingDataEdit->fbo_specific_dropoff;
                    $contactDropoffBefore = $bookingDataEdit->fbo_contact_dropoff;

                    // Mengambil data sesudah
                    $pickupAfter = $request->fbo_pickup;
                    $specificPickupAfter = $request->fbo_specific_pickup;
                    $contactPickupAfter = $request->fbo_contact_pickup;
                    $dropoffAfter = $request->fbo_dropoff;
                    $specificDropoffAfter = $request->fbo_specific_dropoff;
                    $contactDropoffAfter = $request->fbo_contact_dropoff;

                    // Mengecek direction dan menentukan apakah akan update 1 atau 2 data
                    $orderId = $bookingDataEdit->fbo_order_id;
                    $orderData = BookingData::where('fbo_order_id', $orderId)->first();
                    $lastCharacter = substr($orderData->fbo_booking_id, -1); // Mengambil karakter terakhir dari booking ID

                    if ($lastCharacter === 'X') {
                        $direction = 'oneway';
                    } elseif ($lastCharacter === 'Y' || $lastCharacter === 'Z') {
                        $direction = 'roundtrip';
                    } else {
                        // Atur default jika tidak terdeteksi
                        $direction = 'oneway';
                    }

                    // Update data untuk shuttle, menyesuaikan dengan direction
                    if ($direction === 'oneway') {
                        // Update hanya untuk satu data (BookingData pertama)
                        $shuttle = BookingData::find($bookingDataEdit->fbo_id);
                        $shuttle->fbo_pickup = $pickupAfter;
                        $shuttle->fbo_specific_pickup = $specificPickupAfter;
                        $shuttle->fbo_contact_pickup = $contactPickupAfter;
                        $shuttle->fbo_dropoff = $dropoffAfter;
                        $shuttle->fbo_specific_dropoff = $specificDropoffAfter;
                        $shuttle->fbo_contact_dropoff = $contactDropoffAfter;
                        $shuttle->update();
                    } elseif ($direction === 'roundtrip') {
                        // Update booking pertama (berakhiran Y)
                        $shuttle1 = BookingData::where('fbo_order_id', $orderId)
                            ->where('fbo_booking_id', 'like', '%Y')->first(); // Booking ID yang berakhiran 'Y'

                        // Update booking kedua (berakhiran Z)
                        $shuttle2 = BookingData::where('fbo_order_id', $orderId)
                            ->where('fbo_booking_id', 'like', '%Z')->first(); // Booking ID yang berakhiran 'Z'

                        if ($shuttle1) {
                            $shuttle1->fbo_pickup = $pickupAfter;
                            $shuttle1->fbo_specific_pickup = $specificPickupAfter;
                            $shuttle1->fbo_contact_pickup = $contactPickupAfter;
                            $shuttle1->fbo_dropoff = $dropoffAfter;
                            $shuttle1->fbo_specific_dropoff = $specificDropoffAfter;
                            $shuttle1->fbo_contact_dropoff = $contactDropoffAfter;
                            $shuttle1->update();

                            // Log perubahan untuk shuttle pertama (Y)
                            $count = FastboatLog::where('fbl_booking_id', $shuttle1->fbo_booking_id)
                                ->where('fbl_type', 'like', 'Update Shuttle Data%')
                                ->count();

                            FastboatLog::create([
                                'fbl_booking_id' => $shuttle1->fbo_booking_id,
                                'fbl_type' => 'Update Shuttle Data ' . ($count + 1),
                                'fbl_data_before' => 'pickup_poin: ' . $pickupBefore . '| specific_pickup: ' . $specificPickupBefore . '| contact_pickup:' . $contactPickupBefore . '| dropoff_poin: ' . $dropoffBefore . '| specific_dropoff: ' . $specificDropoffBefore . '| contact_dropoff:' . $contactDropoffBefore,
                                'fbl_data_after' => 'pickup_poin: ' . $pickupAfter . '| specific_pickup: ' . $specificPickupAfter . '| contact_pickup:' . $contactPickupAfter . '| dropoff_poin: ' . $dropoffAfter . '| specific_dropoff: ' . $specificDropoffAfter . '| contact_dropoff:' . $contactDropoffAfter,
                            ]);
                        }

                        if ($shuttle2) {
                            // Update booking kedua (berakhiran Z)
                            $shuttle2->fbo_pickup = $pickupAfter;
                            $shuttle2->fbo_specific_pickup = $specificPickupAfter;
                            $shuttle2->fbo_contact_pickup = $contactPickupAfter;
                            $shuttle2->fbo_dropoff = $dropoffAfter;
                            $shuttle2->fbo_specific_dropoff = $specificDropoffAfter;
                            $shuttle2->fbo_contact_dropoff = $contactDropoffAfter;
                            $shuttle2->update();

                            // Log perubahan untuk shuttle kedua (Z) - Return
                            $countSecond = FastboatLog::where('fbl_booking_id', $shuttle2->fbo_booking_id)
                                ->where('fbl_type', 'like', 'Update Shuttle Data%')
                                ->count();

                            FastboatLog::create([
                                'fbl_booking_id' => $shuttle2->fbo_booking_id,
                                'fbl_type' => 'Update Shuttle Data ' . ($countSecond + 1),
                                'fbl_data_before' => 'pickup_poin: ' . $pickupBefore . '| specific_pickup: ' . $specificPickupBefore . '| contact_pickup:' . $contactPickupBefore . '| dropoff_poin: ' . $dropoffBefore . '| specific_dropoff: ' . $specificDropoffBefore . '| contact_dropoff:' . $contactDropoffBefore,
                                'fbl_data_after' => 'pickup_poin: ' . $pickupAfter . '| specific_pickup: ' . $specificPickupAfter . '| contact_pickup:' . $contactPickupAfter . '| dropoff_poin: ' . $dropoffAfter . '| specific_dropoff: ' . $specificDropoffAfter . '| contact_dropoff:' . $contactDropoffAfter,
                            ]);

                            // Update log untuk shuttle kedua (Z) (Return)
                            $shuttle2->fbo_log = $logbefore . $user . ',' . 'Update Shuttle Data' . ',' . $date;
                            $shuttle2->save();
                        }
                    }

                    // Update log di bookingDataEdit untuk shuttle pertama (Y)
                    $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update Shuttle Data' . ',' . $date;
                    $bookingDataEdit->save();

                    // Commit transaksi
                    DB::commit();

                    // Berikan notifikasi setelah commit
                    toast('Data booking has been updated successfully!', 'success');
                    return redirect()->route('trash.view');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
                }
                break;

            case 'customer':
                DB::beginTransaction();
                try {
                    // Mengambil fbo_order_id untuk mendapatkan data booking
                    $orderId = $bookingDataEdit->fbo_order_id;
                    $contact = Contact::where('ctc_id', $orderId)->first();

                    // Mengambil data sebelumnya
                    $nameBefore = $contact->ctc_name;
                    $emailBefore = $contact->ctc_email;
                    $phoneBefore = $contact->ctc_phone;
                    $nationalityBefore = $contact->ctc_nationality;
                    $noteBefore = $contact->ctc_note;

                    // Mengambil data sesudah dari request
                    $nameAfter = $request->ctc_name;
                    $emailAfter = $request->ctc_email;
                    $phoneAfter = $request->ctc_phone;
                    $nationalityAfter = $request->ctc_nationality;
                    $noteAfter = $request->ctc_note;

                    // Menentukan direction (oneway atau roundtrip) berdasarkan booking ID
                    $idOrder = $bookingDataEdit->fbo_order_id;
                    $orderId = BookingData::where('fbo_order_id', $idOrder)->first();
                    $direction = ''; // Default direction
                    if ($orderId) {
                        $lastCharacter = substr($orderId->fbo_booking_id, -1);
                        if ($lastCharacter === 'Y') {
                            $direction = 'roundtrip';
                        } elseif ($lastCharacter === 'X') {
                            $direction = 'oneway';
                        }
                    }

                    // Proses update sesuai dengan direction
                    if ($direction === 'oneway') {
                        // Update hanya 1 data customer (oneway)
                        $contact->update([
                            'ctc_name' => $nameAfter,
                            'ctc_email' => $emailAfter,
                            'ctc_phone' => $phoneAfter,
                            'ctc_nationality' => $nationalityAfter,
                            'ctc_note' => $noteAfter,
                        ]);
                    } elseif ($direction === 'roundtrip') {
                        // Update customer pertama (berakhiran Y)
                        $contact->update([
                            'ctc_name' => $nameAfter,
                            'ctc_email' => $emailAfter,
                            'ctc_phone' => $phoneAfter,
                            'ctc_nationality' => $nationalityAfter,
                            'ctc_note' => $noteAfter,
                        ]);

                        // Log perubahan untuk customer pertama
                        $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                            ->where('fbl_type', 'like', 'Update Customer Data%')
                            ->count();

                        FastboatLog::create([
                            'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                            'fbl_type' => 'Update Customer Data ' . ($count + 1),
                            'fbl_data_before' => 'customer_name: ' . $nameBefore . '| customer_email: ' . $emailBefore . '| customer_phone: ' . $phoneBefore . '| customer_nationality :' . $nationalityBefore . '| customer_note: ' . $noteBefore,
                            'fbl_data_after' => 'customer_name: ' . $nameAfter . '| customer_email:' . $emailAfter . '| customer_phone:' . $phoneAfter . '| customer_nationality:' . $nationalityAfter . '| customer_note:' . $noteAfter,
                        ]);

                        // Update customer kedua (berakhiran Z)
                        $bookingDataSecond = BookingData::where('fbo_order_id', $idOrder)
                            ->where('fbo_booking_id', 'like', substr($bookingDataEdit->fbo_booking_id, 0, -1) . '%')
                            ->whereRaw('RIGHT(fbo_booking_id, 1) = ?', ['Z'])
                            ->first();

                        if ($bookingDataSecond) {
                            // Mengambil contact yang terkait dengan booking kedua (berakhiran Z)
                            $contactSecond = $bookingDataSecond->contact;

                            if ($contactSecond && $contactSecond->ctc_id != $contact->ctc_id) {
                                // Melakukan update data untuk contact kedua
                                $contactSecond->update([
                                    'ctc_name' => $nameAfter,
                                    'ctc_email' => $emailAfter,
                                    'ctc_phone' => $phoneAfter,
                                    'ctc_nationality' => $nationalityAfter,
                                    'ctc_note' => $noteAfter,
                                ]);
                                // Log perubahan untuk customer kedua (Return / Z)
                                $countSecond = FastboatLog::where('fbl_booking_id', $bookingDataSecond->fbo_booking_id)
                                    ->where('fbl_type', 'like', 'Update Customer Data%')
                                    ->count();

                                FastboatLog::create([
                                    'fbl_booking_id' => $bookingDataSecond->fbo_booking_id,
                                    'fbl_type' => 'Update Customer Data ' . ($countSecond + 1),
                                    'fbl_data_before' => 'customer_name: ' . $nameBefore . '| customer_email: ' . $emailBefore . '| customer_phone: ' . $phoneBefore . '| customer_nationality :' . $nationalityBefore . '| customer_note: ' . $noteBefore,
                                    'fbl_data_after' => 'customer_name: ' . $nameAfter . '| customer_email:' . $emailAfter . '| customer_phone:' . $phoneAfter . '| customer_nationality:' . $nationalityAfter . '| customer_note:' . $noteAfter,
                                ]);
                            }
                            // Menyimpan log untuk booking kedua (Z)
                            $bookingDataSecond->fbo_log = $logbefore . $user . ',' . 'Update Customer Data' . ',' . $date;
                            $bookingDataSecond->save();
                        }
                    }

                    // Update log di bookingDataEdit
                    $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update Customer Data' . ',' . $date;
                    $bookingDataEdit->save();

                    // Commit Transaksi
                    DB::commit();

                    // Berikan notifikasi setelah commit
                    toast('Data booking has been updated successfully!', 'success');
                    return redirect()->route('trash.view');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
                }
                break;


            case 'payment':
                DB::beginTransaction();
                try {
                    // Mengambil data sebelumnya
                    $paymentMethodBefore = $bookingDataEdit->fbo_payment_method;
                    $transactionIdBefore = $bookingDataEdit->fbo_transaction_id;

                    // Mengambil data sesudah
                    $paymentMethodAfter = $request->fbo_payment_method;

                    // Menentukan transaction_id berdasarkan payment_method
                    if ($paymentMethodAfter == 'pak_anang') {
                        $fbo_transaction_id = 'received by Mr. Anang';
                    } elseif ($paymentMethodAfter == 'pay_on_port') {
                        $fbo_transaction_id = 'collect';
                    } elseif ($paymentMethodAfter == 'cash') {
                        $fbo_transaction_id = 'received by ' . $request->fbo_transaction_id;
                    } else {
                        $fbo_transaction_id = $request->fbo_transaction_id;
                    }

                    $transactionIdAfter = $fbo_transaction_id;

                    // Menentukan apakah booking tersebut roundtrip atau oneway
                    $idOrder = $bookingDataEdit->fbo_order_id;
                    $orderData = BookingData::where('fbo_order_id', $idOrder)->first();
                    $direction = '';

                    if ($orderData) {
                        $lastCharacter = substr($orderData->fbo_booking_id, -1);
                        if ($lastCharacter === 'Y') {
                            $direction = 'roundtrip';
                        } elseif ($lastCharacter === 'X') {
                            $direction = 'oneway';
                        }
                    }

                    // Proses update sesuai dengan direction
                    if ($direction === 'oneway') {
                        // Update hanya 1 data booking (oneway) berakhiran X
                        $payment = BookingData::find($bookingDataEdit->fbo_id);
                        $payment->fbo_payment_method = $paymentMethodAfter;
                        $payment->fbo_transaction_id = $transactionIdAfter;
                        $payment->update();

                        // Buat log perubahan untuk oneway
                        $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                            ->where('fbl_type', 'like', 'Update Payment Data%')
                            ->count();

                        FastboatLog::create([
                            'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                            'fbl_type' => 'Update Payment Data ' . ($count + 1),
                            'fbl_data_before' => 'payment_method :' . $paymentMethodBefore . '| transaction_id: ' . $transactionIdBefore,
                            'fbl_data_after' => 'payment_method: ' . $paymentMethodAfter . '| transaction_id: ' . $transactionIdAfter,
                        ]);
                    } elseif ($direction === 'roundtrip') {
                        // Update booking pertama (berakhiran Y)
                        $bookingDataEdit->fbo_payment_method = $paymentMethodAfter;
                        $bookingDataEdit->fbo_transaction_id = $transactionIdAfter;
                        $bookingDataEdit->update();

                        // Update booking kedua (berakhiran Z)
                        $bookingIdSecond = BookingData::where('fbo_order_id', $idOrder)
                            ->where('fbo_booking_id', 'like', substr($bookingDataEdit->fbo_booking_id, 0, -1) . '%')
                            ->where('fbo_booking_id', '!=', $bookingDataEdit->fbo_booking_id)
                            ->whereRaw('RIGHT(fbo_booking_id, 1) = ?', ['Z'])
                            ->first();

                        if ($bookingIdSecond) {
                            // Update booking kedua (berakhiran Z)
                            $bookingIdSecond->fbo_payment_method = $paymentMethodAfter;
                            $bookingIdSecond->fbo_transaction_id = $transactionIdAfter;
                            $bookingIdSecond->update();
                        }

                        // Buat log perubahan untuk kedua booking (Y dan Z)
                        $count = FastboatLog::where('fbl_booking_id', $bookingDataEdit->fbo_booking_id)
                            ->where('fbl_type', 'like', 'Update Payment Data%')
                            ->count();

                        FastboatLog::create([
                            'fbl_booking_id' => $bookingDataEdit->fbo_booking_id,
                            'fbl_type' => 'Update Payment Data ' . ($count + 1),
                            'fbl_data_before' => 'payment_method :' . $paymentMethodBefore . '| transaction_id: ' . $transactionIdBefore,
                            'fbl_data_after' => 'payment_method: ' . $paymentMethodAfter . '| transaction_id: ' . $transactionIdAfter,
                        ]);

                        // Buat log perubahan untuk booking kedua (Z)
                        if ($bookingIdSecond) {
                            // Update booking kedua (berakhiran Z)
                            $bookingIdSecond->fbo_payment_method = $paymentMethodAfter;
                            $bookingIdSecond->fbo_transaction_id = $transactionIdAfter;
                            $bookingIdSecond->update();

                            // Simpan log perubahan untuk booking kedua (Z)
                            $countSecond = FastboatLog::where('fbl_booking_id', $bookingIdSecond->fbo_booking_id)
                                ->where('fbl_type', 'like', 'Update Payment Data%')
                                ->count();

                            FastboatLog::create([
                                'fbl_booking_id' => $bookingIdSecond->fbo_booking_id,
                                'fbl_type' => 'Update Payment Data ' . ($countSecond + 1),
                                'fbl_data_before' => 'payment_method :' . $paymentMethodBefore . '| transaction_id: ' . $transactionIdBefore,
                                'fbl_data_after' => 'payment_method: ' . $paymentMethodAfter . '| transaction_id: ' . $transactionIdAfter,
                            ]);

                            // Menyimpan log untuk booking kedua
                            $bookingIdSecond->fbo_log = $logbefore . $user . ',' . 'Update Payment Data' . ',' . $date;
                            $bookingIdSecond->save();
                        }
                    }
                    // Menyimpan log booking
                    $bookingDataEdit->fbo_log = $logbefore . $user . ',' . 'Update Payment Data' . ',' . $date;
                    $bookingDataEdit->save();

                    // Commit transaksi
                    DB::commit();

                    // Berikan notifikasi setelah commit
                    toast('Data booking has been updated successfully!', 'success');
                    return redirect()->route('trash.view');
                } catch (\Exception $e) {
                    DB::rollBack();
                    return back()->withErrors(['error' => 'Failed to update data: ' . $e->getMessage()]);
                }
                break;
        }
    }

    private function generateTicketPdf($ticketId)
    {
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

        $data = [
            'name' => $dataTicket->contact->ctc_name,
            'email' => $dataTicket->contact->ctc_email,
            'phone' => $dataTicket->contact->ctc_phone,
            'note' => $dataTicket->contact->ctc_note,
            'fbo_booking_id' => $dataTicket->fbo_booking_id,
            'fbo_payment_status' => $dataTicket->fbo_payment_status,
            'fbo_trip_date' => $formattedTripDate,
            'fbo_checkin_point_address' => $dataTicket->checkPoint->fcp_address,
            'fbo_checkin_point_maps' => $dataTicket->checkPoint->fcp_maps,
            'fbo_pickup' => $dataTicket->fbo_pickup,
            'fbo_specific_pickup' => $dataTicket->fbo_specific_pickup,
            'fbo_contact_pickup' => $dataTicket->fbo_contact_pickup,
            'fbo_dropoff' => $dataTicket->fbo_dropoff,
            'fbo_specific_dropoff' => $dataTicket->fbo_specific_dropoff,
            'fbo_contact_dropoff' => $dataTicket->fbo_contact_dropoff,
            'cpn_name' => $dataTicket->trip->fastboat->company->cpn_name,
            'cpn_email' => $dataTicket->trip->fastboat->company->cpn_email,
            'cpn_phone' => $dataTicket->trip->fastboat->company->cpn_phone,
            'cpn_logo' => base64_encode(file_get_contents(Helpers\imagePath($dataTicket->trip->fastboat->company->cpn_logo))),
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

        $pdf = Pdf::loadView('ticket.gt', $data);
        return $pdf->output();
    }

    public function emailCustomer($fbo_id)
    {
        try {
            // Cari data booking
            $booking = BookingData::findOrFail($fbo_id);
            $contact = $booking->contact;

            if (!$contact) {
                throw new \Exception('Contact not found');
            }

            $fboBookingId = $booking->fbo_booking_id;
            $bookingType = substr($fboBookingId, -1);

            // Persiapkan variabel untuk konten PDF dan filename
            $pdfContents = [];
            $filenames = [];
            $paths = []; // Untuk menyimpan path file sementara yang dibuat
            $ticketData = []; // Array untuk menyimpan data tiket (bisa 1 atau 2 tiket)

            // Function untuk memformat data tiket
            $formatTicketData = function ($dataTicket) {
                $passengers = explode(';', $dataTicket->fbo_passenger);
                $passengerArray = [];

                foreach ($passengers as $passenger) {
                    $details = explode(',', $passenger);
                    if (count($details) === 4) {
                        $age = (int) $details[1];
                        if ($age > 13) {
                            $ageGroup = 'ADULT';
                        } elseif ($age >= 3 && $age <= 12) {
                            $ageGroup = 'CHILD';
                        } elseif ($age >= 0 && $age <= 2) {
                            $ageGroup = 'INFANT';
                        } else {
                            $ageGroup = 'UNKNOWN';
                        }

                        $passengerArray[] = [
                            'name' => $details[0],
                            'age' => $ageGroup,
                            'gender' => $details[2],
                            'nationality' => $details[3],
                        ];
                    }
                }

                $tripDate = new \DateTime($dataTicket->fbo_trip_date);
                $time = $dataTicket->availability->fba_dept_time ?? $dataTicket->trip->fbt_dept_time;
                $timeDateTime = new \DateTime($time);
                $arrivaltime = new \DateTime($dataTicket->fbo_arrival_time);
                $bookingDate = new \DateTime($dataTicket->created_at);

                $logoPath = Helpers\imagePath($dataTicket->trip->fastboat->company->cpn_logo);
                $logoBase64 = '';
                if (file_exists($logoPath)) {
                    $logoType = mime_content_type($logoPath);
                    $logoContent = file_get_contents($logoPath);
                    if ($logoContent !== false) {
                        $logoBase64 = 'data:' . $logoType . ';base64,' . base64_encode($logoContent);
                    }
                }

                $adult_currency = $dataTicket->fbo_adult_currency * $dataTicket->fbo_adult;
                $child_currency = $dataTicket->fbo_child_currency * $dataTicket->fbo_child;

                return [
                    'name' => $dataTicket->contact->ctc_name,
                    'email' => $dataTicket->contact->ctc_email,
                    'phone' => $dataTicket->contact->ctc_phone,
                    'note' => $dataTicket->contact->ctc_note,
                    'fbo_booking_id' => $dataTicket->fbo_booking_id,
                    'fbo_payment_status' => $dataTicket->fbo_payment_status,
                    'fbo_trip_date' => $tripDate->format('l, d M Y'),
                    'fbo_checkin_point_address' => $dataTicket->checkPoint->fcp_address,
                    'fbo_checkin_point_maps' => $dataTicket->checkPoint->fcp_maps,
                    'fbo_pickup' => $dataTicket->fbo_pickup,
                    'fbo_specific_pickup' => $dataTicket->fbo_specific_pickup,
                    'fbo_contact_pickup' => $dataTicket->fbo_contact_pickup,
                    'fbo_dropoff' => $dataTicket->fbo_dropoff,
                    'fbo_specific_dropoff' => $dataTicket->fbo_specific_dropoff,
                    'fbo_contact_dropoff' => $dataTicket->fbo_contact_dropoff,
                    'cpn_name' => $dataTicket->trip->fastboat->company->cpn_name,
                    'cpn_email' => $dataTicket->trip->fastboat->company->cpn_email,
                    'cpn_phone' => $dataTicket->trip->fastboat->company->cpn_phone,
                    'cpn_logo' =>  url('storage/' . $dataTicket->trip->fastboat->company->cpn_logo),
                    'fb_name' => $dataTicket->trip->fastboat->fb_name,
                    'fbt_name' => $dataTicket->trip->fbt_name,
                    'fbo_currency' => $dataTicket->fbo_currency,
                    'fbo_adult_currency' => $adult_currency,
                    'fbo_child_currency' => $child_currency,
                    'fbo_end_total_currency' => $dataTicket->fbo_end_total_currency,
                    'fbo_adult' => $dataTicket->fbo_adult,
                    'fbo_child' => $dataTicket->fbo_child,
                    'fbo_infant' => $dataTicket->fbo_infant,
                    'departure_port' => $dataTicket->trip->departure->prt_name_en,
                    'departure_island' => $dataTicket->trip->departure->island->isd_name,
                    'departure_time' => $timeDateTime->format('H:i'),
                    'arrival_port' => $dataTicket->trip->arrival->prt_name_en,
                    'arrival_island' => $dataTicket->trip->arrival->island->isd_name,
                    'arrival_time' => $arrivaltime->format('H:i'),
                    'passengers' => $passengerArray,
                    'logo_ticket' => base64_encode(file_get_contents(public_path('assets/images/logo-ticket.png'))),
                    'created_at' => $bookingDate->format('l, d M Y'),
                    'is_return' => substr($dataTicket->fbo_booking_id, -1) === 'Z', // Menandai apakah ini tiket return
                ];
            };

            // Load data untuk tiket pertama (X atau Y)
            $dataTicket = BookingData::with([
                'trip.fastboat',
                'trip.fastboat.company',
                'trip.departure',
                'trip.arrival',
                'contact',
                'availability'
            ])->findOrFail($fbo_id);

            $ticketData[] = $formatTicketData($dataTicket);

            // Generate PDF untuk tiket pertama
            $pdfContent = $this->generateTicketPdf($fbo_id);
            $filename = 'Ticket_' . $fboBookingId . '.pdf';
            $path = storage_path('app/temp/' . $filename);
            file_put_contents($path, $pdfContent);
            $pdfContents[] = $pdfContent;
            $filenames[] = $filename;
            $paths[] = $path;

            // Jika round trip (Y), tambahkan tiket return (Z)
            if ($bookingType === 'Y') {
                $returnBooking = BookingData::where('fbo_booking_id', substr($fboBookingId, 0, -1) . 'Z')
                    ->with([
                        'trip.fastboat',
                        'trip.fastboat.company',
                        'trip.departure',
                        'trip.arrival',
                        'contact',
                        'availability'
                    ])
                    ->first();

                if ($returnBooking) {
                    // Tambahkan data tiket return
                    $ticketData[] = $formatTicketData($returnBooking);

                    // Generate PDF untuk tiket return
                    $pdfContentReturn = $this->generateTicketPdf($returnBooking->fbo_id);
                    $filenameReturn = 'Ticket_' . $returnBooking->fbo_booking_id . '.pdf';
                    $pathReturn = storage_path('app/temp/' . $filenameReturn);
                    file_put_contents($pathReturn, $pdfContentReturn);
                    $pdfContents[] = $pdfContentReturn;
                    $filenames[] = $filenameReturn;
                    $paths[] = $pathReturn;
                }
            }

            // Kirim email dengan data lengkap
            Mail::to($contact->ctc_email)
                ->send(new CustomerMail($contact, [
                    'pdf_contents' => $pdfContents,
                    'filenames' => $filenames,
                    'ticket_data' => $ticketData,
                ]));

            // Hapus file PDF yang telah disimpan sementara
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            toast('Email has been delivered successfully!', 'success');
            return redirect()->route('trash.view');
        } catch (\Exception $e) {
            // Hapus file PDF yang telah disimpan sementara meskipun terjadi error
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            toast('Failed to deliver email: ' . $e->getMessage(), 'error');
            return redirect()->route('trash.view');
        }
    }

    public function emailCompany($fbo_id)
    {
        try {
            // Cari data booking
            $booking = BookingData::with([
                'trip.fastboat',
                'trip.fastboat.company',
                'trip.departure',
                'trip.arrival',
                'contact',
                'availability'
            ])->findOrFail($fbo_id);

            // Pastikan data perusahaan tersedia
            if (!$booking->availability?->trip?->fastboat?->company) {
                throw new \Exception('Company data not found');
            }

            $fboBookingId = $booking->fbo_booking_id;
            $bookingType = substr($fboBookingId, -1); // Y = single trip, Z = return trip
            $departCompany = $booking->availability->trip->fastboat->company;

            // Function untuk memformat data tiket
            $formatTicketData = function ($dataTicket) {
                $passengers = explode(';', $dataTicket->fbo_passenger);
                $passengerArray = [];

                foreach ($passengers as $passenger) {
                    $details = explode(',', $passenger);
                    if (count($details) === 4) {
                        $age = (int) $details[1];
                        $ageGroup = $age > 13 ? 'Adult' : ($age >= 3 && $age <= 12 ? 'Child' : ($age >= 0 ? 'Infant' : 'Unknown'));

                        $passengerArray[] = [
                            'name' => $details[0],
                            'age' => $ageGroup,
                            'gender' => $details[2],
                            'nationality' => $details[3],
                        ];
                    }
                }

                $tripDate = new \DateTime($dataTicket->fbo_trip_date);
                $time = $dataTicket->availability->fba_dept_time ?? $dataTicket->trip->fbt_dept_time;
                $timeDateTime = new \DateTime($time);
                $arrivaltime = new \DateTime($dataTicket->fbo_arrival_time);
                $bookingDate = new \DateTime($dataTicket->created_at);

                return [
                    'name' => $dataTicket->contact->ctc_name,
                    'email' => $dataTicket->contact->ctc_email,
                    'phone' => $dataTicket->contact->ctc_phone,
                    'note' => $dataTicket->contact->ctc_note,
                    'fbo_booking_id' => $dataTicket->fbo_booking_id,
                    'fbt_name' => $dataTicket->trip->fbt_name,
                    'fbo_trip_date' => $tripDate->format('l, d M Y'),
                    'departure_port' => $dataTicket->trip->departure->prt_name_en,
                    'arrival_port' => $dataTicket->trip->arrival->prt_name_en,
                    'departure_time' => $timeDateTime->format('H:i'),
                    'arrival_time' => $arrivaltime->format('H:i'),
                    'passengers' => $passengerArray,
                    'company_name' => $dataTicket->trip->fastboat->company->cpn_name,
                    'company_email' => $dataTicket->trip->fastboat->company->cpn_email,
                    'fastboat_name' => $dataTicket->trip->fastboat->fb_name,
                    'company_logo' => url('storage/' . $dataTicket->trip->fastboat->company->cpn_logo),
                ];
            };

            // Format data tiket keberangkatan
            $departTicketData = $formatTicketData($booking);
            $emailsSent = false;

            if ($bookingType === 'Y') {
                // Handle round trip
                $returnBooking = BookingData::where('fbo_booking_id', substr($fboBookingId, 0, -1) . 'Z')
                    ->with([
                        'trip.fastboat',
                        'trip.fastboat.company',
                        'trip.departure',
                        'trip.arrival',
                        'contact',
                        'availability'
                    ])
                    ->first();

                if ($returnBooking) {
                    $returnCompany = $returnBooking->availability->trip->fastboat->company;
                    $returnTicketData = $formatTicketData($returnBooking);

                    if ($departCompany->cpn_id === $returnCompany->cpn_id) {
                        // Same company - send one email
                        if ($departCompany->cpn_email_status == 1) {
                            Mail::to($departCompany->cpn_email)
                                ->send(new SupplierMail($booking, $departCompany->cpn_id, [$departTicketData, $returnTicketData]));
                            $emailsSent = true;
                            toast('Round trip email has been delivered to ' . $departCompany->cpn_name . '!', 'success');
                        } else {
                            toast('Email cannot be sent, company email status is inactive for ' . $departCompany->cpn_name, 'error');
                        }
                    } else {
                        // Different companies - send separate emails
                        $messages = [];

                        if ($departCompany->cpn_email_status == 1) {
                            Mail::to($departCompany->cpn_email)
                                ->send(new SupplierMail($booking, $departCompany->cpn_id, [$departTicketData]));
                            $emailsSent = true;
                            $messages[] = 'Departure email sent to ' . $departCompany->cpn_name;
                        } else {
                            $messages[] = 'Departure email could not be sent to ' . $departCompany->cpn_name . ' (inactive email status)';
                        }

                        if ($returnCompany->cpn_email_status == 1) {
                            Mail::to($returnCompany->cpn_email)
                                ->send(new SupplierMail($returnBooking, $returnCompany->cpn_id, [$returnTicketData]));
                            $emailsSent = true;
                            $messages[] = 'Return email sent to ' . $returnCompany->cpn_name;
                        } else {
                            $messages[] = 'Return email could not be sent to ' . $returnCompany->cpn_name . ' (inactive email status)';
                        }

                        toast(implode("\n", $messages), $emailsSent ? 'success' : 'error');
                    }
                }
            } else {
                // Single trip - send email
                if ($departCompany->cpn_email_status == 1) {
                    Mail::to($departCompany->cpn_email)
                        ->send(new SupplierMail($booking, $departCompany->cpn_id, [$departTicketData]));
                    $emailsSent = true;
                    toast('Email has been delivered to ' . $departCompany->cpn_name . '!', 'success');
                } else {
                    toast('Email cannot be sent, company email status is inactive for ' . $departCompany->cpn_name, 'error');
                }
            }

            if (!$emailsSent) {
                toast('No emails were sent due to inactive email status.', 'warning');
            }

            return redirect()->route('trash.view');
        } catch (\Exception $e) {
            toast('Failed to deliver email: ' . $e->getMessage(), 'error');
            return redirect()->route('trash.view');
        }
    }
}
