<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\Contact;
use App\Models\FastboatAvailability;
use App\Models\FastboatCheckinPoint;
use App\Models\FastboatTrip;
use App\Models\MasterCurrency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingApiController extends Controller
{
    function generateOrderId()
    {
        // Ambil order terakhir dari database, jika ada
        $lastOrder = Contact::orderBy('ctc_id', 'desc')->first();

        if (!$lastOrder) {
            // Jika belum ada order, mulai dengan "AAAAA"
            return 'AAAAA';
        }

        // Ambil order_id terakhir
        $lastOrderId = $lastOrder->ctc_order_id;

        // Konversi order_id menjadi array karakter
        $characters = str_split($lastOrderId);

        // Loop dari belakang untuk increment karakter terakhir
        for ($i = count($characters) - 1; $i >= 0; $i--) {
            if ($characters[$i] == 'Z') {
                // Jika karakter terakhir adalah Z, ubah jadi A
                $characters[$i] = 'A';
            } else {
                // Jika bukan Z, tambahkan 1 ke karakter tersebut
                $characters[$i] = chr(ord($characters[$i]) + 1);
                break;
            }
        }

        // Jika semua karakter adalah 'Z', tambahkan satu karakter lagi
        if (implode('', $characters) == str_repeat('A', count($characters))) {
            array_unshift($characters, 'A');
        }

        // Gabungkan karakter menjadi string lagi
        return implode('', $characters);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'contact.ctc_name' => 'required|string',
            'contact.ctc_email' => 'required|email',
            'contact.ctc_phone' => 'required|string',
            'contact.ctc_nationality' => 'required|string',
            'trip.ids' => 'required|array',
            'trip.ids.*' => 'required|string',
            'passengers' => 'required|array',
            'passengers.*.name' => 'required|string',
            'passengers.*.age' => 'required|integer',
            'passengers.*.gender' => 'required|string|in:male,female',
            'passengers.*.nationality' => 'required|string',
            'currency.code' => 'required|string',
        ]);

        // Mendapatkan IP address
        $ipAddress = $request->ip(); // IP Publik pengguna

        // Jika berada di belakang proxy, coba ambil IP dari X-Forwarded-For
        if ($request->server('HTTP_X_FORWARDED_FOR')) {
            $ipAddress = $request->server('HTTP_X_FORWARDED_FOR');
        } else {
            $ipAddress = $request->ip();
        }

        // contact
        $contactData = new Contact();
        $contactData->ctc_order_id = $this->generateOrderId();
        $contactData->ctc_name = $validatedData['contact']['ctc_name'];
        $contactData->ctc_email = $validatedData['contact']['ctc_email'];
        $contactData->ctc_phone = $validatedData['contact']['ctc_phone'];
        $contactData->ctc_nationality = $validatedData['contact']['ctc_nationality'];
        $contactData->ctc_note = $request->ctc_note;
        $contactData->ctc_order_type = 'F';
        $contactData->ctc_booking_date = Carbon::now()->toDateString();
        $contactData->ctc_booking_time = Carbon::now()->toTimeString();
        $contactData->ctc_ip_address = $ipAddress;
        $contactData->ctc_browser = $request->header('User-Agent');
        $contactData->ctc_updated_by = Auth()->id() ?? 'api';
        $contactData->ctc_created_by = Auth()->id() ?? 'api';
        $contactData->save();

        // Ambil ctc_id kontak yang baru dibuat
        $contactId = $contactData->ctc_id;

        // Jika masih null, coba query ulang
        if ($contactId === null) {
            $latestContact = Contact::latest('ctc_id')->first();
            $contactId = $latestContact ? $latestContact->ctc_id : null;
        }

        if ($contactId === null) {
            throw new \Exception('Failed to retrieve Contact ctc_id');
        }

        // trip
        $tripIds = $validatedData['trip']['ids'];

        // Mengambil trip yang tersedia
        $trips = FastboatAvailability::whereIn('fba_id', $tripIds)->get();

        // Menentukan tipe perjalanan
        $departureSuffix = count($tripIds) > 1 ? 'Y' : 'X';

        // Memeriksa apakah ada trip yang ditemukan
        if ($trips->isEmpty()) {
            // Mengembalikan response jika tidak ada trip yang ditemukan
            return response()->json([
                'message' => 'No trips found for the provided IDs.',
            ], 404);
        }

        // Mengambil ID ketersediaan trip pertama
        $availabilityId = $trips->first()->fba_id;

        $bookingDataDepart = new BookingData();
        $bookingDataDepart->fbo_order_id = $contactId;
        $bookingDataDepart->fbo_booking_id = 'F' . $contactData->ctc_order_id . $departureSuffix;
        $bookingDataDepart->fbo_availability_id = $availabilityId;

        $trip = FastboatAvailability::find($availabilityId);

        if ($trip) {
            // Mengambil fba_trip_id
            $bookingDataDepart->fbo_trip_id = $trip->fba_trip_id;
        } else {
            // Menangani jika tidak ditemukan
            throw new \Exception("FastboatAvailability with ID {$availabilityId} not found.");
        }

        $bookingDataDepart->fbo_payment_method = "";
        $bookingDataDepart->fbo_transaction_id = "";
        $bookingDataDepart->fbo_payment_status = "unpaid";
        $bookingDataDepart->fbo_transaction_status = "waiting";

        // Hitung penumpang
        $passengerStrings = [];

        foreach ($validatedData['passengers'] as $passengerData) {
            // Format data penumpang
            $passengerString = implode(',', [
                $passengerData['name'],
                $passengerData['age'],
                $passengerData['gender'],
                $passengerData['nationality']
            ]);

            $passengerStrings[] = $passengerString;
        }
        $passengersFormatted = implode(';', $passengerStrings);

        // Menghitung total penumpang berdasarkan kategori
        $totalAdult = 0;
        $totalChild = 0;
        $totalInfant = 0;
        foreach ($validatedData['passengers'] as $passenger) {
            if ($passenger['age'] <= 2) {
                $totalInfant++;
            } elseif ($passenger['age'] >= 3 && $passenger['age'] <= 12) {
                $totalChild++;
            } elseif ($passenger['age'] >= 13) {
                $totalAdult++;
            }
        }

        $totalPassenger = $totalAdult + $totalChild;

        // Menyimpan total penumpang dalam variabel request untuk digunakan selanjutnya
        $request->merge([
            'fbo_adult' => $totalAdult,
            'fbo_child' => $totalChild,
            'fbo_infant' => $totalInfant,
        ]);
        $availabilityData = FastboatAvailability::find($availabilityId);
        $bookingDataDepart->fbo_trip_date = $availabilityData->fba_date;

        // Mencari harga
        $bookingDataDepart->fbo_currency = $validatedData['currency']['code'];


        if ($availabilityData) {
            $bookingDataDepart->fbo_adult_nett = $availabilityData->fba_adult_nett ?? null;
            $bookingDataDepart->fbo_child_nett = $availabilityData->fba_child_nett ?? null;
            $bookingDataDepart->fbo_total_nett = ($bookingDataDepart->fbo_adult_nett * $totalAdult) + ($bookingDataDepart->fbo_child_nett * $totalChild);
            $bookingDataDepart->fbo_adult_publish = $availabilityData->fba_adult_publish ?? null;
            $bookingDataDepart->fbo_child_publish = $availabilityData->fba_child_publish ?? null;
            $bookingDataDepart->fbo_total_publish = ($bookingDataDepart->fbo_adult_publish * $totalAdult) + ($bookingDataDepart->fbo_child_publish * $bookingDataDepart->$totalChild);
        } else {
            // Menangani jika tidak ada data untuk availability ID
            throw new \Exception("No availability data found for ID {$availabilityId}.");
        }

        $bookingDataDepart->fbo_passenger = $passengersFormatted;
        $bookingDataDepart->fbo_adult = $totalAdult;
        $bookingDataDepart->fbo_child = $totalChild;
        $bookingDataDepart->fbo_infant = $totalInfant;

        // Mencari nilai kurs yang sesuai dengan fbo_currency
        $currency = MasterCurrency::where('cy_code', $bookingDataDepart->fbo_currency)->first();

        if (!$currency) {
            throw new \Exception("Rate {$bookingDataDepart->fbo_currency} not found.");
        }

        $bookingDataDepart->fbo_kurs = $currency->cy_rate;
        if ($bookingDataDepart->fbo_kurs == 0) {
            return response()->json([
                'message' => 'Failed to get rate',
            ], 200);
        } else {
            $bookingDataDepart->fbo_adult_currency = round($bookingDataDepart->fbo_adult_publish / $bookingDataDepart->fbo_kurs);
            $bookingDataDepart->fbo_child_currency = round($bookingDataDepart->fbo_child_publish / $bookingDataDepart->fbo_kurs);
            $bookingDataDepart->fbo_total_currency = round(($bookingDataDepart->fbo_adult_currency * $totalAdult) + ($bookingDataDepart->fbo_child_currency * $totalChild));
        }
        $discount =  $availabilityData->fba_discount;
        $bookingDataDepart->fbo_discount = $discount * ($totalAdult + $totalChild);
        $bookingDataDepart->fbo_price_cut = 0;
        $bookingDataDepart->fbo_discount_total = $bookingDataDepart->fbo_discount + $bookingDataDepart->fbo_price_cut;
        $bookingDataDepart->fbo_refund = 0;
        $bookingDataDepart->fbo_end_total = $bookingDataDepart->fbo_total_publish;
        $bookingDataDepart->fbo_end_total_currency = $bookingDataDepart->fbo_total_currency;
        $bookingDataDepart->fbo_profit = $bookingDataDepart->fbo_end_total - $bookingDataDepart->fbo_total_nett;
        $bookingDataDepart->fbo_fast_boat = $availabilityData->trip->fastboat->fb_id;
        $bookingDataDepart->fbo_company = $availabilityData->trip->fastboat->company->cpn_id;
        $bookingDataDepart->fbo_departure_island = $availabilityData->trip->departure->island->isd_id;
        $bookingDataDepart->fbo_departure_port = $availabilityData->trip->departure->prt_id;
        $bookingDataDepart->fbo_departure_time = $availabilityData->trip->fbt_dept_time;
        $bookingDataDepart->fbo_arrival_island = $availabilityData->trip->arrival->island->isd_id;
        $bookingDataDepart->fbo_arrival_port = $availabilityData->trip->arrival->prt_id;
        $bookingDataDepart->fbo_arrival_time = $availabilityData->fba_arrival_time ?? $availabilityData->trip->fbt_arrival_time;
        $bookingDataDepart->fbo_checkin_point = FastboatCheckinPoint::where('fcp_company', $bookingDataDepart->fbo_company)->value('fcp_id');
        $bookingDataDepart->fbo_pickup = $request->fbo_pickup;
        $bookingDataDepart->fbo_dropoff = $request->fbo_dropoff;
        $bookingDataDepart->fbo_specific_pickup = $request->fbo_specific_pickup;
        $bookingDataDepart->fbo_specific_dropoff = $request->fbo_specific_dropoff;
        $bookingDataDepart->fbo_contact_pickup = $request->fbo_contact_pickup;
        $bookingDataDepart->fbo_contact_dropoff = $request->fbo_contact_dropoff;
        $bookingDataDepart->fbo_mail_admin = "";
        $bookingDataDepart->fbo_mail_client = "";
        $bookingDataDepart->fbo_log = "";
        $bookingDataDepart->fbo_source = "website";
        $bookingDataDepart->save();

        // Pengecekan ketersediaan stok sekaligus penanganan race condition
        $stokDataDepart = FastboatAvailability::where('fba_id', $availabilityId)->lockForUpdate()->first();
        if ($stokDataDepart->fba_stock < $totalPassenger  + 1) {
            return response()->json(['message' => 'Ticket stock is low'], 400);
        }

        // Pengurangan stok di availability
        $stokDataDepart->fba_stock -= $totalPassenger;
        $stokDataDepart->save();

        // return
        if (count($tripIds) > 1) {
            $availabilityReturnId = $tripIds[1];
            $availabilityDataReturn = FastboatAvailability::find($availabilityReturnId);
            $tripDate = $availabilityDataReturn->fba_date;
            $bookingDataReturn = new BookingData();
            $bookingDataReturn->fbo_order_id = $contactId;
            $bookingDataReturn->fbo_booking_id = 'F' . $contactData->ctc_order_id . 'Z';
            $bookingDataReturn->fbo_availability_id = $availabilityReturnId;
            $bookingDataReturn->fbo_trip_date = $tripDate;

            // Mendapatkan id trip dari fast-boat availability
            $trip = FastboatAvailability::find($availabilityReturnId);

            if ($trip) {
                // Mengambil fba_trip_id
                $bookingDataReturn->fbo_trip_id = $trip->fba_trip_id;
            } else {
                // Menangani jika tidak ditemukan
                throw new \Exception("FastboatAvailability with ID {$availabilityReturnId} not found.");
            }

            $bookingDataReturn->fbo_payment_method = $bookingDataDepart->fbo_payment_method;
            $bookingDataReturn->fbo_transaction_id = $bookingDataDepart->fbo_transaction_id;
            $bookingDataReturn->fbo_payment_status = $bookingDataDepart->fbo_payment_status;
            $bookingDataReturn->fbo_transaction_status = $bookingDataDepart->fbo_transaction_status;
            $bookingDataReturn->fbo_currency = $validatedData['currency']['code'];
            if ($availabilityDataReturn) {
                $bookingDataReturn->fbo_adult_nett = $availabilityDataReturn->fba_adult_nett ?? null;
                $bookingDataReturn->fbo_child_nett = $availabilityDataReturn->fba_child_nett ?? null;
                $bookingDataReturn->fbo_total_nett = ($bookingDataReturn->fbo_adult_nett * $totalAdult) + ($bookingDataReturn->fbo_child_nett * $totalChild);
                $bookingDataReturn->fbo_adult_publish = $availabilityDataReturn->fba_adult_publish ?? null;
                $bookingDataReturn->fbo_child_publish = $availabilityDataReturn->fba_child_publish ?? null;
                $bookingDataReturn->fbo_total_publish = ($bookingDataReturn->fbo_adult_publish * $totalAdult) + ($bookingDataReturn->fbo_child_publish * $bookingDataReturn->$totalChild);
            } else {
                // Menangani jika tidak ada data untuk availability ID
                throw new \Exception("No availability data found for ID {$availabilityReturnId}.");
            }
            $bookingDataReturn->fbo_passenger = $passengersFormatted;
            $bookingDataReturn->fbo_adult = $totalAdult;
            $bookingDataReturn->fbo_child = $totalChild;
            $bookingDataReturn->fbo_infant = $totalInfant;
            $bookingDataReturn->fbo_kurs = $currency->cy_rate;
            if ($bookingDataReturn->fbo_kurs == 0) {
                return response()->json([
                    'message' => 'Failed to get rate',
                ], 200);
            } else {
                $bookingDataReturn->fbo_adult_currency = round($bookingDataReturn->fbo_adult_publish / $bookingDataReturn->fbo_kurs);
                $bookingDataReturn->fbo_child_currency = round($bookingDataReturn->fbo_child_publish / $bookingDataReturn->fbo_kurs);
                $bookingDataReturn->fbo_total_currency = round(($bookingDataReturn->fbo_adult_currency * $totalAdult) + ($bookingDataReturn->fbo_child_currency * $totalChild));
            }
            $discount =  $availabilityDataReturn->fba_discount;
            $bookingDataReturn->fbo_discount = $discount * ($totalAdult + $totalChild);
            $bookingDataReturn->fbo_price_cut = 0;
            $bookingDataReturn->fbo_discount_total = $bookingDataReturn->fbo_discount + $bookingDataReturn->fbo_price_cut;
            $bookingDataReturn->fbo_refund = 0;
            $bookingDataReturn->fbo_end_total = $bookingDataReturn->fbo_total_publish;
            $bookingDataReturn->fbo_end_total_currency = $bookingDataReturn->fbo_total_currency;
            $bookingDataReturn->fbo_profit = $bookingDataReturn->fbo_end_total - $bookingDataReturn->fbo_total_nett;
            $bookingDataReturn->fbo_fast_boat = $availabilityDataReturn->trip->fastboat->fb_id;
            $bookingDataReturn->fbo_company = $availabilityDataReturn->trip->fastboat->company->cpn_id;
            $bookingDataReturn->fbo_departure_island = $availabilityDataReturn->trip->departure->island->isd_id;
            $bookingDataReturn->fbo_departure_port = $availabilityDataReturn->trip->departure->prt_id;
            $bookingDataReturn->fbo_departure_time = $availabilityDataReturn->trip->fbt_dept_time;
            $bookingDataReturn->fbo_arrival_island = $availabilityDataReturn->trip->arrival->island->isd_id;
            $bookingDataReturn->fbo_arrival_port = $availabilityDataReturn->trip->arrival->prt_id;
            $bookingDataReturn->fbo_arrival_time = $availabilityDataReturn->fba_arrival_time ?? $availabilityDataReturn->trip->fbt_arrival_time;
            $bookingDataReturn->fbo_checkin_point = FastboatCheckinPoint::where('fcp_company', $bookingDataReturn->fbo_company)->value('fcp_id');
            $bookingDataReturn->fbo_pickup = $request->fbo_pickup;
            $bookingDataReturn->fbo_dropoff = $request->fbo_dropoff;
            $bookingDataReturn->fbo_specific_pickup = $request->fbo_specific_pickup;
            $bookingDataReturn->fbo_specific_dropoff = $request->fbo_specific_dropoff;
            $bookingDataReturn->fbo_contact_pickup = $request->fbo_contact_pickup;
            $bookingDataReturn->fbo_contact_dropoff = $request->fbo_contact_dropoff;
            $bookingDataReturn->fbo_mail_admin = "";
            $bookingDataReturn->fbo_mail_client = "";
            $bookingDataReturn->fbo_log = "";
            $bookingDataReturn->fbo_source = "website";
            $bookingDataReturn->save();

            // Pengecekan ketersediaan stok sekaligus penanganan race condition
            $stokDatareturn = FastboatAvailability::where('fba_id', $availabilityReturnId)->lockForUpdate()->first();
            if ($stokDatareturn->fba_stock < $totalPassenger  + 1) {
                return response()->json(['message' => 'Ticket stock is low'], 400);
            }

            // Pengurangan stok di availability
            $stokDatareturn->fba_stock -= $totalPassenger;
            $stokDatareturn->save();
        } else {
            unset($availabilityReturnId);
        }


        $invoiceResponse = $this->createXenditInvoice($contactId);

        return response()->json([
            "depart" => $bookingDataDepart,
            "return" => isset($bookingDataReturn) ? $bookingDataReturn : null,
            "invoice" => $invoiceResponse->getData()
        ]);
    }

    public function createXenditInvoice($contactId)
    {
        try {
            // Fetch the contact and booking data
            $contact = Contact::findOrFail($contactId);

            // Fetch booking data for depart, return, and one-way (if exists)
            $bookingDepart = BookingData::where('fbo_order_id', $contactId)
                ->where('fbo_booking_id', 'like', '%Y')
                ->first();

            $bookingReturn = BookingData::where('fbo_order_id', $contactId)
                ->where('fbo_booking_id', 'like', '%Z')
                ->first();

            $bookingOneWay = BookingData::where('fbo_order_id', $contactId)
                ->where('fbo_booking_id', 'like', '%X')
                ->first();

            // Calculate total amount based on booking types
            $totalAmount = 0;

            if ($bookingDepart) {
                $totalAmount += $bookingDepart->fbo_end_total;
            }

            if ($bookingReturn) {
                $totalAmount += $bookingReturn->fbo_end_total;
            }

            if ($bookingOneWay) {
                $totalAmount += $bookingOneWay->fbo_end_total;
            }

            if ($totalAmount <= 0) {
                return response()->json([
                    'error' => 'No valid bookings found or total amount is zero'
                ], 400);
            }

            // Prepare invoice data
            $invoiceData = [
                'external_id' => $contact->ctc_order_id,
                'amount' => $totalAmount,
                'payer_email' => $contact->ctc_email,
                'description' => $contact->ctc_note ?? 'Fastboat Booking',
                'payment_methods' => ['BNI'],
            ];

            // URL Endpoint Xendit
            $url = 'https://api.xendit.co/v2/invoices';

            // Send HTTP Request to Xendit API
            $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
                ->post($url, [
                    'external_id' => $invoiceData['external_id'],
                    'amount' => $invoiceData['amount'],
                    'payer_email' => $invoiceData['payer_email'],
                    'description' => $invoiceData['description'],
                    'payment_methods' => $invoiceData['payment_methods'],
                    'success_redirect_url' => url('/payment-success'),
                    'failure_redirect_url' => url('/payment-failure'),
                ]);

            if ($response->successful()) {
                $invoice = $response->json();

                // Update booking data with invoice information
                if ($bookingDepart) {
                    $bookingDepart->fbo_transaction_id = $invoice['id'] ?? null;
                    $bookingDepart->fbo_payment_method = 'Xendit';
                    $bookingDepart->save();
                }

                if ($bookingReturn) {
                    $bookingReturn->fbo_transaction_id = $invoice['id'] ?? null;
                    $bookingReturn->fbo_payment_method = 'Xendit';
                    $bookingReturn->save();
                }

                if ($bookingOneWay) {
                    $bookingOneWay->fbo_transaction_id = $invoice['id'] ?? null;
                    $bookingOneWay->fbo_payment_method = 'Xendit';
                    $bookingOneWay->save();
                }

                return response()->json([
                    'invoice_url' => $invoice['invoice_url'],
                    'invoice_id' => $invoice['id']
                ]);
            } else {
                return response()->json([
                    'error' => 'Failed to create Xendit invoice',
                    'details' => $response->json()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception in creating Xendit invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
