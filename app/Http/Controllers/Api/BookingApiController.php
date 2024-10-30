<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\Contact;
use App\Models\FastboatAvailability;
use App\Models\FastboatTrip;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        // trip
        $tripIds = $validatedData['trip']['ids'];
        $trips = FastboatAvailability::whereIn('fba_id', $tripIds)->get();

        // passengers
        $passengerStrings = []; // Array untuk menyimpan string penumpang

        foreach ($validatedData['passengers'] as $passengerData) {
            // Format data penumpang
            $passengerString = implode(',', [
                $passengerData['name'],
                $passengerData['age'],
                $passengerData['gender'],
                $passengerData['nationality']
            ]);

            $passengerStrings[] = $passengerString; // Tambahkan ke array
        }
        // Gabungkan semua string penumpang dengan semicolon
        $passengersFormatted = implode(';', $passengerStrings);
        dd($passengersFormatted);
    }
}
