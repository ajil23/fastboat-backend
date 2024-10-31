<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FastboatAvailability;
use App\Models\FastboatShuttle;
use App\Models\FastboatShuttleArea;
use App\Models\MasterCurrency;
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

    // Tambahkan fungsi ini di dalam kelas yang sama
    private function customRound($value)
    {
        if ($value - floor($value) >= 0.5) {
            return ceil($value); // Jika >= 0.5, bulatkan ke atas
        }
        return floor($value); // Jika < 0.5, bulatkan ke bawah
    }

    public function search(Request $request)
    {
        // Ambil data dari request
        $direction = $request->input('direction');
        $portNameDeparture = strtolower($request->input('port_name_departure'));
        $portNameArrival = strtolower($request->input('port_name_arrival'));
        $departureDate = $request->input('departure_date');
        $returnDate = $request->input('return_date');
        $currencyCode = $request->input('currency'); // Ambil parameter currency

        // Ambil jumlah penumpang
        $adult = (int) $request->input('adult', 0);
        $child = (int) $request->input('child', 0);
        $infant = (int) $request->input('infant', 0);
        $totalPax = $adult + $child + $infant;

        // Validasi tanggal
        if (!$departureDate || !\Carbon\Carbon::canBeCreatedFromFormat($departureDate, 'Y-m-d')) {
            return response()->json(['success' => false, 'message' => 'Invalid departure date format. Use Y-m-d.']);
        }
        if ($returnDate && !\Carbon\Carbon::canBeCreatedFromFormat($returnDate, 'Y-m-d')) {
            return response()->json(['success' => false, 'message' => 'Invalid return date format. Use Y-m-d.']);
        }

        // Ambil rate mata uang jika ada
        $currency = MasterCurrency::where('cy_code', $currencyCode)->first();
        $currencyRate = $currency ? $currency->cy_rate : 1; // Default ke 1 jika tidak ada

        $availabilities = [];

        // Pencarian untuk one trip
        if ($direction === 'one_way') {
            $oneTripAvailabilities = FastboatAvailability::with(['trip.fastboat.company', 'trip.departure.island', 'trip.arrival.island'])
                ->whereHas('trip.departure', function ($query) use ($portNameDeparture) {
                    $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameDeparture]);
                })
                ->whereHas('trip.arrival', function ($query) use ($portNameArrival) {
                    $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameArrival]);
                })
                ->where('fba_date', $departureDate)
                ->when($totalPax > 0, function ($query) use ($totalPax) {
                    return $query->where('fba_stock', '>=', $totalPax);
                })
                ->get()
                ->map(function ($availability) use ($currencyRate) {
                    $availability->fba_dept_time = $availability->fba_dept_time ?? $availability->trip->fbt_dept_time;
                    $availability->fba_arrival_time = $availability->fba_arrival_time ?? $availability->trip->fbt_arrival_time;

                    // Ambil shuttle & shuttle area
                    $shuttleType = null;
                    $shuttleOption = null;
                    $pickupAreas = [];
                    $dropoffAreas = [];

                    if ($availability) {
                        $trip = $availability->trip->fbt_id;
                        $shuttleType = $availability->trip->fbt_shuttle_type;
                        $shuttleOption = $availability->trip->fbt_shuttle_option;

                        foreach ($availability as $avail) {
                            if ($shuttleType && in_array($shuttleType, ['Private', 'Sharing'])) {
                                $trip_id = $trip;
                                $Areas = FastboatShuttle::where('s_trip', $trip_id)->get();
                                
                                if ($shuttleOption === 'pickup') {
                                    // For pickup option
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
                                } elseif ($shuttleOption === 'drop') {
                                    // For drop option
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
                    return [
                        'fbt_recom' => $availability->trip->fbt_recom,
                        'fb_image1' => url('storage/' . ltrim($availability->trip->fastboat->fb_image1, '/')) ?? null,
                        'fba_dept_time' => $availability->fba_dept_time,
                        'fba_arrival_time' => $availability->fba_arrival_time,
                        'dept_port' => $availability->trip->departure->prt_name_en,
                        'dept_island' => $availability->trip->departure->island->isd_name ?? null,
                        'arrival_port' => $availability->trip->arrival->prt_name_en,
                        'arrival_island' => $availability->trip->arrival->island->isd_name ?? null,
                        'cpn_logo' => url('storage/' . ltrim($availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                        'cpn_name' => $availability->trip->fastboat->company->cpn_name ?? null,
                        'fba_adult_publish' => $this->customRound($availability->fba_adult_publish / $currencyRate), // Konversi dan pembulatan
                        'fba_child_publish' => $this->customRound($availability->fba_child_publish / $currencyRate), // Konversi dan pembulatan
                        'shuttle_type' => $shuttleType,
                        'shuttle_option' => $shuttleOption,
                        'fbo_pickups' => $pickupAreas,
                        'fbo_dropoffs' => $dropoffAreas,
                    ];
                });

            $availabilities['one_trip'] = $oneTripAvailabilities;
        } elseif ($direction === 'round_trip') {
            if (!$returnDate) {
                return response()->json(['success' => false, 'message' => 'Return date is required for round trip.']);
            }

            // Pencarian untuk keberangkatan
            $departureTripAvailabilities = FastboatAvailability::with(['trip.fastboat.company', 'trip.departure.island', 'trip.arrival.island'])
                ->whereHas('trip.departure', function ($query) use ($portNameDeparture) {
                    $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameDeparture]);
                })
                ->whereHas('trip.arrival', function ($query) use ($portNameArrival) {
                    $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameArrival]);
                })
                ->where('fba_date', $departureDate)
                ->when($totalPax > 0, function ($query) use ($totalPax) {
                    return $query->where('fba_stock', '>=', $totalPax);
                })
                ->get()
                ->map(function ($availability) use ($currencyRate) {
                    $availability->fba_dept_time = $availability->fba_dept_time ?? $availability->trip->fbt_dept_time;
                    $availability->fba_arrival_time = $availability->fba_arrival_time ?? $availability->trip->fbt_arrival_time;

                    // Ambil shuttle & shuttle area
                    $shuttleType = null;
                    $shuttleOption = null;
                    $pickupAreas = [];
                    $dropoffAreas = [];

                    if ($availability) {
                        $trip = $availability->trip->fbt_id;
                        $shuttleType = $availability->trip->fbt_shuttle_type;
                        $shuttleOption = $availability->trip->fbt_shuttle_option;

                        foreach ($availability as $avail) {
                            if ($shuttleType && in_array($shuttleType, ['Private', 'Sharing'])) {
                                $trip_id = $trip;
                                $Areas = FastboatShuttle::where('s_trip', $trip_id)->get();
                                
                                if ($shuttleOption === 'pickup') {
                                    // For pickup option
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
                                } elseif ($shuttleOption === 'drop') {
                                    // For drop option
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
                    return [
                        'fbt_recom' => $availability->trip->fbt_recom,
                        'fb_image1' => url('storage/' . ltrim($availability->trip->fastboat->fb_image1, '/')) ?? null,
                        'fba_dept_time' => $availability->fba_dept_time,
                        'fba_arrival_time' => $availability->fba_arrival_time,
                        'dept_port' => $availability->trip->departure->prt_name_en,
                        'dept_island' => $availability->trip->departure->island->isd_name ?? null,
                        'arrival_port' => $availability->trip->arrival->prt_name_en,
                        'arrival_island' => $availability->trip->arrival->island->isd_name ?? null,
                        'cpn_logo' => url('storage/' . ltrim($availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                        'cpn_name' => $availability->trip->fastboat->company->cpn_name ?? null,
                        'fba_adult_publish' => $this->customRound($availability->fba_adult_publish / $currencyRate), // Konversi dan pembulatan
                        'fba_child_publish' => $this->customRound($availability->fba_child_publish / $currencyRate), // Konversi dan pembulatan
                        'shuttle_type' => $shuttleType,
                        'shuttle_option' => $shuttleOption,
                        'fbo_pickups' => $pickupAreas,
                        'fbo_dropoffs' => $dropoffAreas,
                    ];
                });

            $availabilities['departure_trip'] = $departureTripAvailabilities;

            // Pencarian untuk kembali
            $returnTripAvailabilities = FastboatAvailability::with(['trip.fastboat.company', 'trip.departure.island', 'trip.arrival.island'])
                ->whereHas('trip.departure', function ($query) use ($portNameArrival) {
                    $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameArrival]);
                })
                ->whereHas('trip.arrival', function ($query) use ($portNameDeparture) {
                    $query->whereRaw('LOWER(prt_name_en) = ?', [$portNameDeparture]);
                })
                ->where('fba_date', $returnDate)
                ->when($totalPax > 0, function ($query) use ($totalPax) {
                    return $query->where('fba_stock', '>=', $totalPax);
                })
                ->get()
                ->map(function ($availability) use ($currencyRate) {
                    $availability->fba_dept_time = $availability->fba_dept_time ?? $availability->trip->fbt_dept_time;
                    $availability->fba_arrival_time = $availability->fba_arrival_time ?? $availability->trip->fbt_arrival_time;

                    // Ambil shuttle & shuttle area
                    $shuttleType = null;
                    $shuttleOption = null;
                    $pickupAreas = [];
                    $dropoffAreas = [];

                    if ($availability) {
                        $trip = $availability->trip->fbt_id;
                        $shuttleType = $availability->trip->fbt_shuttle_type;
                        $shuttleOption = $availability->trip->fbt_shuttle_option;

                        foreach ($availability as $avail) {
                            if ($shuttleType && in_array($shuttleType, ['Private', 'Sharing'])) {
                                $trip_id = $trip;
                                $Areas = FastboatShuttle::where('s_trip', $trip_id)->get();
                                
                                if ($shuttleOption === 'pickup') {
                                    // For pickup option
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
                                } elseif ($shuttleOption === 'drop') {
                                    // For drop option
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
                    return [
                        'fbt_recom' => $availability->trip->fbt_recom,
                        'fb_image1' => url('storage/' . ltrim($availability->trip->fastboat->fb_image1, '/')) ?? null,
                        'fba_dept_time' => $availability->fba_dept_time,
                        'fba_arrival_time' => $availability->fba_arrival_time,
                        'dept_port' => $availability->trip->departure->prt_name_en,
                        'dept_island' => $availability->trip->departure->island->isd_name ?? null,
                        'arrival_port' => $availability->trip->arrival->prt_name_en,
                        'arrival_island' => $availability->trip->arrival->island->isd_name ?? null,
                        'cpn_logo' => url('storage/' . ltrim($availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                        'cpn_name' => $availability->trip->fastboat->company->cpn_name ?? null,
                        'fba_adult_publish' => $this->customRound($availability->fba_adult_publish / $currencyRate), // Konversi dan pembulatan
                        'fba_child_publish' => $this->customRound($availability->fba_child_publish / $currencyRate), // Konversi dan pembulatan
                        'shuttle_type' => $shuttleType,
                        'shuttle_option' => $shuttleOption,
                        'fbo_pickups' => $pickupAreas,
                        'fbo_dropoffs' => $dropoffAreas,
                    ];
                });

            $availabilities['return_trip'] = $returnTripAvailabilities;
        }

        return response()->json(['success' => true, 'data' => $availabilities]);
    }
}
