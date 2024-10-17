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
        $direction = $request->input('direction'); // Ambil tipe perjalanan
        $portNameDeparture = strtolower($request->input('port_name_departure'));
        $portNameArrival = strtolower($request->input('port_name_arrival'));
        $departureDate = $request->input('departure_date');
        $returnDate = $request->input('return_date');

        $adult = (int) $request->input('adult', 0);
        $child = (int) $request->input('child', 0);
        $infant = (int) $request->input('infant', 0);

        // Validasi tanggal
        if (!$departureDate || !\Carbon\Carbon::canBeCreatedFromFormat($departureDate, 'Y-m-d')) {
            return response()->json(['success' => false, 'message' => 'Invalid departure date format. Use Y-m-d.']);
        }
        if ($returnDate && !\Carbon\Carbon::canBeCreatedFromFormat($returnDate, 'Y-m-d')) {
            return response()->json(['success' => false, 'message' => 'Invalid return date format. Use Y-m-d.']);
        }

        $totalPax = $adult + $child + $infant;
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
                ->map(function ($availability) {
                    $availability->fba_dept_time = $availability->fba_dept_time ?? $availability->trip->fbt_dept_time;
                    $availability->fba_arrival_time = $availability->fba_arrival_time ?? $availability->trip->fbt_arrival_time;

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
                        'fba_adult_publish' => $availability->fba_adult_publish,
                        'fba_child_publish' => $availability->fba_child_publish,
                    ];
                });

            $availabilities['one_trip'] = $oneTripAvailabilities;

            // Pencarian untuk round trip
        } elseif ($direction === 'round_trip') {
            if (!$returnDate) {
                return response()->json(['success' => false, 'message' => 'Return date is required for round trip.']);
            }

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
                ->map(function ($availability) {
                    $availability->fba_dept_time = $availability->fba_dept_time ?? $availability->trip->fbt_dept_time;
                    $availability->fba_arrival_time = $availability->fba_arrival_time ?? $availability->trip->fbt_arrival_time;

                    return [
                        'fbt_recom' => $availability->trip->fbt_recom,
                        'fb_image1' => url($availability->trip->fastboat->fb_image1) ?? null,
                        'fba_dept_time' => $availability->fba_dept_time,
                        'fba_arrival_time' => $availability->fba_arrival_time,
                        'dept_port' => $availability->trip->departure->prt_name_en,
                        'dept_island' => $availability->trip->departure->island->isd_name ?? null,
                        'arrival_port' => $availability->trip->arrival->prt_name_en,
                        'arrival_island' => $availability->trip->arrival->island->isd_name ?? null,
                        'cpn_logo' => url('storage/' . ltrim($availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                        'cpn_name' => $availability->trip->fastboat->company->cpn_name ?? null,
                        'fba_adult_publish' => $availability->fba_adult_publish,
                        'fba_child_publish' => $availability->fba_child_publish,
                    ];
                });

            $availabilities['departure_trip'] = $departureTripAvailabilities;

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
                ->map(function ($availability) {
                    $availability->fba_dept_time = $availability->fba_dept_time ?? $availability->trip->fbt_dept_time;
                    $availability->fba_arrival_time = $availability->fba_arrival_time ?? $availability->trip->fbt_arrival_time;

                    return [
                        'fbt_recom' => $availability->trip->fbt_recom,
                        'fb_image1' => url('storage/' . ltrim($availability->trip->fastboat->fb_image1, '/')) ?? null,
                        'fba_dept_time' => $availability->fba_dept_time,
                        'fba_arrival_time' => $availability->fba_arrival_time,
                        'dept_port' => $availability->trip->departure->prt_name_en,
                        'dept_island' => $availability->trip->departure->island->isd_name ?? null,
                        'arrival_port' => $availability->trip->arrival->prt_name_en,
                        'arrival_island' => $availability->trip->arrival->island->isd_name ?? null,
                        'cpn_logo' => url($availability->trip->fastboat->company->cpn_logo) ?? null,
                        'cpn_name' => $availability->trip->fastboat->company->cpn_name ?? null,
                        'fba_adult_publish' => $availability->fba_adult_publish,
                        'fba_child_publish' => $availability->fba_child_publish,
                    ];
                });

            $availabilities['return_trip'] = $returnTripAvailabilities;
        }

        return response()->json(['success' => true, 'data' => $availabilities]);
    }
}
