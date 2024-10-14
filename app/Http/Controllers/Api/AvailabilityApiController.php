<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FastboatAvailability;
use Illuminate\Http\Request;

class AvailabilityApiController extends Controller
{
    public function index()
    {
        $data = FastboatAvailability::all();

        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
        ], 200);
    }

    public function search(Request $request)
    {
        $portNameDeparture = strtolower($request->input('port_name_departure')); // Ambil nama departure port
        $portNameArrival = strtolower($request->input('port_name_arrival')); // Ambil nama arrival port
        $date = $request->input('date');
        $minPax = $request->input('min_pax'); // Ambil nilai minimum penumpang

        // Validasi tanggal
        if (!$date || !\Carbon\Carbon::canBeCreatedFromFormat($date, 'Y-m-d')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid date format. Use Y-m-d.'
            ]);
        }

        // Ambil availabilities berdasarkan nama departure port, arrival port, tanggal, dan minimum penumpang
        $availabilities = FastboatAvailability::whereHas('trip.departure', function ($query) use ($portNameDeparture) {
            $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameDeparture]);
        })
            ->whereHas('trip.arrival', function ($query) use ($portNameArrival) {
                $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameArrival]);
            })
            ->where('fba_date', $date)
            ->when($minPax, function ($query) use ($minPax) {
                return $query->where('fba_min_pax', '>=', $minPax); // Filter berdasarkan min pax
            })
            ->get();

        return response()->json([
            'success' => true,
            'data' => $availabilities
        ]);
    }
}
