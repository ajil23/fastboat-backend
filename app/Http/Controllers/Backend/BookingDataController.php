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

            // Query untuk FastboatAvailability
            $availabilityQuery = FastboatAvailability::whereHas('trip.departure', function ($query) use ($departurePort) {
                $query->where('prt_name_en', $departurePort);
            })
                ->whereHas('trip.arrival', function ($query) use ($arrivalPort) {
                    $query->where('prt_name_en', $arrivalPort);
                })
                ->whereHas('trip.fastboat', function ($query) use ($fastBoat) {
                    $query->where('fb_name', $fastBoat);
                })
                ->where('fba_date', $tripDate);

            // Filter berdasarkan timeDept, jika ada
            if ($timeDept) {
                $availabilityQuery->where(function ($query) use ($timeDept) {
                    $query->where('fba_dept_time', $timeDept)
                        ->orWhereHas('trip', function ($query) use ($timeDept) {
                            $query->where('fbt_dept_time', $timeDept);
                        });
                });
            }

            // Ambil data FastboatAvailability
            $availability = $availabilityQuery->get();

            // Jika tidak ada data di FastboatAvailability, ambil dari trip saja
            if ($availability->isEmpty()) {
                $tripQuery = SchedulesTrip::whereHas('departure', function ($query) use ($departurePort) {
                    $query->where('prt_name_en', $departurePort);
                })
                    ->whereHas('arrival', function ($query) use ($arrivalPort) {
                        $query->where('prt_name_en', $arrivalPort);
                    })
                    ->whereHas('fastboat', function ($query) use ($fastBoat) {
                        $query->where('fb_name', $fastBoat);
                    })
                    ->where('fbt_dept_time', $timeDept)
                    ->whereDate('fbt_trip_date', $tripDate);

                $trips = $tripQuery->get();

                if ($trips->isEmpty()) {
                    return response()->json(['message' => 'No availability found'], 404);
                }

                // Jika availability tidak ada, gunakan data dari trip
                $availability = $trips->map(function ($trip) {
                    return (object)[
                        'fba_adult_publish' => null,
                        'fba_child_publish' => null,
                        'fba_adult_nett' => null,
                        'fba_child_nett' => null,
                        'fba_discount' => null,
                        'fba_dept_time' => null,
                        'trip' => $trip,
                    ];
                });
            }

            // Buat HTML dan perhitungan
            $html = '';
            $cardTitle = '';
            $adultPublishTotal = 0;
            $childPublishTotal = 0;

            foreach ($availability as $avail) {
                $trip = $avail->trip;

                // Tentukan waktu keberangkatan (prioritas fba_dept_time jika ada, jika tidak ada gunakan fbt_dept_time dari trip)
                $deptTime = $avail->fba_dept_time ?? $trip->fbt_dept_time;

                // Buat card-title dengan format (Nama Fastboat (Code Departure -> Code Arrival Time Dept))
                $cardTitle = '<center>' . $trip->fastboat->fb_name . ' (' .
                    $trip->departure->prt_code . ' -> ' .
                    $trip->arrival->prt_code . ' ' .
                    date('H:i', strtotime($deptTime)) . ')' . '</center>';

                $html .= '<tr>';
                $html .= '<td><center>' . number_format($avail->fba_adult_publish ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_child_publish ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_adult_nett ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_child_nett ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_discount ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '</tr>';

                // Update perhitungan total
                $adultPublishTotal += $avail->fba_adult_publish ?? 0;
                $childPublishTotal += $avail->fba_child_publish ?? 0;
            }

            // Return hasil HTML dan perhitungan
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

    public function getFilteredData(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDate = $request->input('trip_date');
            $departurePort = $request->input('departure_port');
            $arrivalPort = $request->input('arrival_port');
            $fastBoat = $request->input('fast_boat');

            // Filter berdasarkan tanggal
            $query = FastboatAvailability::where('fba_date', $tripDate);

            // Filter berdasarkan departure port
            if ($departurePort) {
                $query->whereHas('trip.departure', function ($q) use ($departurePort) {
                    $q->where('prt_name_en', $departurePort);
                });
            }

            // Filter berdasarkan arrival port
            if ($arrivalPort) {
                $query->whereHas('trip.arrival', function ($q) use ($arrivalPort) {
                    $q->where('prt_name_en', $arrivalPort);
                });
            }

            // Filter berdasarkan fastboat
            if ($fastBoat) {
                $query->whereHas('trip.fastboat', function ($q) use ($fastBoat) {
                    $q->where('fb_name', $fastBoat);
                });
            }

            $availabilities = $query->get();

            // Format data untuk dropdown
            $departurePorts = [];
            $arrivalPorts = [];
            $fastBoats = [];
            $timeDepts = [];

            foreach ($availabilities as $availability) {
                $trip = $availability->trip;

                // Mengumpulkan departure port
                if (!in_array($trip->departure->prt_name_en, $departurePorts)) {
                    $departurePorts[] = $trip->departure->prt_name_en;
                }

                // Mengumpulkan arrival port
                if (!in_array($trip->arrival->prt_name_en, $arrivalPorts)) {
                    $arrivalPorts[] = $trip->arrival->prt_name_en;
                }

                // Mengumpulkan fastboat
                if (!in_array($trip->fastboat->fb_name, $fastBoats)) {
                    $fastBoats[] = $trip->fastboat->fb_name;
                }

                // Mengumpulkan waktu keberangkatan
                $deptTime = $availability->fba_dept_time ?? $trip->fbt_dept_time;
                $formattedTimeDept = date('H:i', strtotime($deptTime));

                if (!in_array($formattedTimeDept, $timeDepts)) {
                    $timeDepts[] = $formattedTimeDept;
                }
            }

            // Return data untuk setiap dropdown
            return response()->json([
                'departure_ports' => $departurePorts,
                'arrival_ports' => $arrivalPorts,
                'fast_boats' => $fastBoats,
                'time_depts' => $timeDepts,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
