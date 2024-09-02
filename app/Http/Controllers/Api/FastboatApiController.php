<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DataFastboat;
use App\Models\SchedulesTrip;
use Illuminate\Http\Request;

class FastboatApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = DataFastboat::all();

        return response()->json([
            'data' => $data,
        ], 200);
    }

    public function paginate()
    {
        $data = DataFastboat::paginate(4);

        return response()->json([
            'data' => $data,
        ]);
    }

    public function show_en($fb_slug_en)
    {
        // Mencari post berdasarkan slug dan memuat data terkait dari tabel ScheduleTrip
        $data = DataFastboat::with('scheduleTrips.deptPort', 'scheduleTrips.arrivalPort')
            ->where('fb_slug_en', $fb_slug_en)
            ->first();

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $result = [
            'fb_id' => $data->fb_id,
            'fb_name' => $data->fb_name,
            'fb_description_en' => $data->fb_description_en,
            'fb_content_en' => $data->fb_content_en,
            'fb_image1' => $data->fb_image1,
            'fb_image2' => $data->fb_image2,
            'fb_image3' => $data->fb_image3,
            'fb_image4' => $data->fb_image4,
            'fb_image5' => $data->fb_image5,
            'fb_image6' => $data->fb_image6,
        ];

        // Menambahkan schedule_trips jika ada
        if ($data->scheduleTrips->isNotEmpty()) {
            $result['schedule_trips'] = $data->scheduleTrips->map(function ($scheduleTrip) {
                return [
                    'fbt_id' => $scheduleTrip->fbt_id,
                    'fbt_name' => $scheduleTrip->fbt_name,
                    'departure_port' => $scheduleTrip->deptPort ? $scheduleTrip->deptPort->prt_name_en : null,
                    'departure_time' => $scheduleTrip->fbt_dept_time,
                    'arrival_port' => $scheduleTrip->arrivalPort ? $scheduleTrip->arrivalPort->prt_name_en : null,
                    'arrival_time' => $scheduleTrip->fbt_arrival_time,
                    'departure_port_slug' => $scheduleTrip->deptPort ? $scheduleTrip->deptPort->prt_slug_en : null,
                    'arrival_port_slug' => $scheduleTrip->arrivalPort ? $scheduleTrip->arrivalPort->prt_slug_en : null,
                ];
            });
        }

        return response()->json($result, 200);
    }


    public function show_idn($fb_slug_idn)
    {
        // Mencari post berdasarkan slug
        $data = DataFastboat::where('fb_slug_idn', $fb_slug_idn)->first();

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($data->scheduleTrips->isEmpty()) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        $result = [
            'fb_id' => $data->fb_id,
            'fb_name' => $data->fb_name,
            'fb_description_idn' => $data->fb_description_idn,
            'fb_content_idn' => $data->fb_content_idn,
            'fb_image1' => $data->fb_image1,
            'fb_image2' => $data->fb_image2,
            'fb_image3' => $data->fb_image3,
            'fb_image4' => $data->fb_image4,
            'fb_image5' => $data->fb_image5,
            'fb_image6' => $data->fb_image6,
            'schedule_trips' => $data->scheduleTrips->map(function ($scheduleTrip) {
                return [
                    'fbt_id' => $scheduleTrip->fbt_id,
                    'fbt_name' => $scheduleTrip->fbt_name,
                    'departure_port' => $scheduleTrip->deptPort ? $scheduleTrip->deptPort->prt_name_idn : null,
                    'departure_time' => $scheduleTrip->fbt_dept_time,
                    'arrival_port' => $scheduleTrip->arrivalPort ? $scheduleTrip->arrivalPort->prt_name_idn : null,
                    'arrival_time' => $scheduleTrip->fbt_arrival_time,
                ];
            })
        ];

        return response()->json($result, 200);
    }
}
