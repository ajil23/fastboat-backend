<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\FastboatAvailability;
use App\Models\SchedulesTrip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class FastboatAvailabilityController extends Controller
{
    // this function is for view all data from fastboat table
    public function index()
    {
        $availability = FastboatAvailability::all();
        $trip = SchedulesTrip::with(['departure.arrival.fastboat']);
        return view('fast-boat.availability.index', compact('availability', 'trip'));
    }

    public function add()
    {
        $trip = SchedulesTrip::all();
        return view('fast-boat.availability.add', compact('trip'));
    }

    public function store(Request $request)
    {
        // Mengambil rentang tanggal yang dipilih
        $dates = explode(" to ", $request->fba_date);
    
        // Konversi format tanggal
        $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[0]));
        $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', trim($dates[1]));
    
        // Mengambil array trip ID yang dipilih
        $tripIds = $request->fba_trip_id;
    
        // Iterasi setiap tanggal dari awal hingga akhir
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            foreach ($tripIds as $tripId) {
                $availabilityData = new FastboatAvailability();
                $availabilityData->fba_trip_id = $tripId;
                $availabilityData->fba_date = $date->format('Y-m-d');
                $availabilityData->fba_dept_time = $request->fba_dept_time;
                $availabilityData->fba_arriv_time = $request->fba_arriv_time;
                $availabilityData->fba_adult_nett = $request->fba_adult_nett;
                $availabilityData->fba_child_nett = $request->fba_child_nett;
                $availabilityData->fba_adult_publish = $request->fba_adult_publish;
                $availabilityData->fba_child_publish = $request->fba_child_publish;
                $availabilityData->fba_discount = $request->fba_discount;
                $availabilityData->fba_stock = $request->fba_stock;
                $availabilityData->fba_status = $request->fba_status;
                $availabilityData->fba_shuttle_status = $request->fba_shuttle_status;
                $availabilityData->fba_info = $request->fba_info;
                $availabilityData->fba_created_by = auth()->id();
                $availabilityData->fba_updated_by = auth()->id();
                $availabilityData->save();
            }
        }
    
        // Menambahkan pesan toast sukses ke dalam session
        toast('Your data has been submitted!', 'success');
        return redirect()->route('availability.view');
    }

    public function extend() 
    {
        $trip = SchedulesTrip::all();
        return view('fast-boat.availability.extend', compact('trip'));    
    }
    
}
