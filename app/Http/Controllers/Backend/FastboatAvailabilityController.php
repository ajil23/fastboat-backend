<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\DataCompany;
use App\Models\DataFastboat;
use App\Models\DataRoute;
use App\Models\FastboatAvailability;
use App\Models\MasterPort;
use App\Models\SchedulesSchedule;
use App\Models\SchedulesTrip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class FastboatAvailabilityController extends Controller
{
    // this function is for view all data from fastboat table
    public function index()
    {
        // Fetch all availability data sorted by date
        $availability = FastboatAvailability::with(['company', 'schedule', 'route', 'departure', 'island'])->orderBy('fba_date')->get();

        // Get the earliest and latest dates
        $startDate = $availability->first()->fba_date ?? now();
        $endDate = $availability->last()->fba_date ?? now();

        $company = DataCompany::all();
        $fastboat = DataFastboat::all();
        $schedule = SchedulesSchedule::all();
        $route = DataRoute::all();
        $departure = MasterPort::all();
        $arrival = MasterPort::all();
        $deptTime = SchedulesTrip::all();
        $trip = SchedulesTrip::with(['departure.arrival.fastboat']);
        return view('fast-boat.availability.index', compact('availability', 'trip', 'fastboat', 'company', 'schedule', 'route', 'departure', 'arrival', 'deptTime', 'startDate', 'endDate'));
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

    public function extend(Request $request)
    {
        // Validasi input trip ID
        $tripIds = $request->input('fba_trip_id', []);

        // Mengambil data availability berdasarkan trip ID yang dipilih
        $availability = FastboatAvailability::all();
        $trip = SchedulesTrip::all();

        return view('fast-boat.availability.extend', compact('availability', 'trip'));
    }

    public function storeExtend(Request $request)
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
                // Cari data availability yang sudah ada
                $existingAvailability = FastboatAvailability::where('fba_trip_id', $tripId)
                    ->where('fba_date', $date->format('Y-m-d'))
                    ->first();
    
                if ($existingAvailability) {
                    // Update data yang ada dengan informasi baru
                    $existingAvailability->fba_stock = $request->fba_stock;  // Update stock
                    $existingAvailability->fba_status = $request->fba_status; // Update status
                    $existingAvailability->fba_updated_by = auth()->id();
                    $existingAvailability->save();
                } else {
                    // Jika tidak ada data yang ada, buat data baru
                    $templateAvailability = FastboatAvailability::where('fba_trip_id', $tripId)->orderBy('fba_date', 'desc')->first();
    
                    if ($templateAvailability) {
                        $availabilityData = new FastboatAvailability();
                        $availabilityData->fba_trip_id = $tripId;
                        $availabilityData->fba_date = $date->format('Y-m-d');
                        $availabilityData->fba_dept_time = $templateAvailability->fba_dept_time;
                        $availabilityData->fba_arriv_time = $templateAvailability->fba_arriv_time;
                        $availabilityData->fba_adult_nett = $templateAvailability->fba_adult_nett;
                        $availabilityData->fba_child_nett = $templateAvailability->fba_child_nett;
                        $availabilityData->fba_adult_publish = $templateAvailability->fba_adult_publish;
                        $availabilityData->fba_child_publish = $templateAvailability->fba_child_publish;
                        $availabilityData->fba_discount = $templateAvailability->fba_discount;
                        $availabilityData->fba_stock = $request->fba_stock; // Update stock
                        $availabilityData->fba_status = $request->fba_status; // Update status
                        $availabilityData->fba_shuttle_status = $templateAvailability->fba_shuttle_status;
                        $availabilityData->fba_info = $templateAvailability->fba_info;
                        $availabilityData->fba_created_by = auth()->id();
                        $availabilityData->fba_updated_by = auth()->id();
                        $availabilityData->save();
                    }
                }
            }
        }
    
        toast('Your data has been extended successfully!', 'success');
        return redirect()->route('availability.view');
    }  
}
