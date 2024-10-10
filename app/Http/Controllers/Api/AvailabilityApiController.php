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
        // Validasi parameter dari request
        $validated = $request->validate([
            'departure_port' => 'required|integer|exists:masterport,prt_id',
            'arrival_port' => 'required|integer|exists:masterport,prt_id',
            'departure_time' => 'required|date',
            'return_date' => 'nullable|date',
            'min_pax' => 'required|integer|min:1',
        ]);

        // Query availability berdasarkan data pelabuhan dan waktu
        $availabilities = FastboatAvailability::whereHas('trip', function ($query) use ($validated) {
                // Query untuk mencocokkan pelabuhan keberangkatan dan tujuan
                $query->where('fbt_dept_port', $validated['departure_port'])
                      ->where('fbt_arrival_port', $validated['arrival_port']);
            })
            // Filter berdasarkan tanggal keberangkatan
            ->where('fba_date', '=', $validated['departure_time'])
            // Jika ada return date, tambahkan filter opsional
            ->when($validated['return_date'], function ($query) use ($validated) {
                $query->where('fba_date', '=', $validated['return_date']);
            })
            // Filter berdasarkan jumlah penumpang minimal
            ->where('fba_min_pax', '<=', $validated['min_pax'])
            ->where('fba_status', '=', 'active') // Hanya trip yang aktif
            ->get();

        // Kembalikan data dalam format JSON
        return response()->json([
            'data' => $availabilities
        ]);
    }
}
