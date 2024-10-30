<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\Contact;
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
        dd($request);
    }
}
