<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataFastboat;
use App\Models\DataRoute;
use App\Models\FastboatAvailability;
use App\Models\MasterPort;
use App\Models\SchedulesTrip;
use Illuminate\Http\Request;

class BookingDataController extends Controller
{
    public function index()
    {
        return view('booking.data.index');
    }

    public function add()
    {
        $trip = SchedulesTrip::all();
        $fastboat = DataFastboat::all();
        $departure = MasterPort::all();
        $arrival = MasterPort::all();
        $route = DataRoute::all();
        $deptTime = SchedulesTrip::all();
        $availability = FastboatAvailability::all();
        return view('booking.data.add', compact('trip', 'fastboat', 'departure', 'arrival', 'route', 'deptTime', 'availability'));
    }

    public function store()
    {
        return redirect()->route('data.view');
    }

    public function search(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDate = $request->input('trip_date');
            $departurePort = $request->input('departure_port');
            $arrivalPort = $request->input('arrival_port');
            $fastBoat = $request->input('fast_boat');
            $timeDept = $request->input('time_dept');

            \Log::info('Trip date: ' . $tripDate);
            \Log::info('Departure port: ' . $departurePort);

            // Cari trip berdasarkan departur dan arrival port di SchedulesTrip
            $trips = SchedulesTrip::whereHas('departure', function ($query) use ($departurePort) {
                $query->where('prt_name_en', $departurePort);
            })
                ->whereHas('arrival', function ($query) use ($arrivalPort) {
                    $query->where('prt_name_en', $arrivalPort);
                })
                ->whereHas('fastboat', function ($query) use ($fastBoat) {
                    $query->where('fb_name', $fastBoat);
                })
                ->where('fbt_dept_time', $timeDept)
                ->get();

            // Ambil availability berdasarkan trip_date dari FastboatAvailability
            $availability = FastboatAvailability::whereIn('fba_trip_id', $trips->pluck('fbt_id'))
                ->where('fba_date', $tripDate)
                ->get();

            if ($availability->isEmpty()) {
                return response()->json(['message' => 'No availability found'], 404);
            }

            // Buat HTML untuk tabel dan judul card-title
            $html = '';
            $cardTitle = '';

            foreach ($availability as $avail) {
                $trip = $avail->trip; // Mengakses relasi ke SchedulesTrip

                // Buat card-title dengan format (Nama Fastboat (Code Departure -> Code Arrival Time Dept))
                $cardTitle = '<center>' . $trip->fastboat->fb_name . ' (' .
                    $trip->departure->prt_code . ' -> ' .
                    $trip->arrival->prt_code . ' ' .
                    date('H:i', strtotime($trip->fbt_dept_time)) . ')' . '</center>';

                $html .= '<tr>';
                $html .= '<td><center>' . number_format($avail->fba_adult_publish, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_child_publish, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_adult_nett, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_child_nett, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_discount, 0, ',', '.') . '</center></td>';
                $html .= '</tr>';
            }

            // Return HTML dan card title
            return response()->json([
                'html' => $html,
                'card_title' => $cardTitle
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
