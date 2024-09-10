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
            $adultPublishTotal = 0;
            $childPublishTotal = 0;

            foreach ($availability as $avail) {
                $trip = $avail->trip;

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

                // Update perhitungan
                $adultPublishTotal += $avail->fba_adult_publish;
                $childPublishTotal += $avail->fba_child_publish;
            }

            // Return HTML dan perhitungan
            return response()->json([
                'html' => $html,
                'card_title' => $cardTitle,
                'adult_publish' => number_format($adultPublishTotal, 0, ',', '.'),
                'child_publish' => number_format($childPublishTotal, 0, ',', '.'),
                'total_end' => number_format($adultPublishTotal + $childPublishTotal, 0, ',', '.'),
                'currency_end' => number_format($adultPublishTotal + $childPublishTotal, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
