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
        $company = DataCompany::get(["cpn_name", "cpn_id"]);
        $fastboat = DataFastboat::all();
        $schedule = SchedulesSchedule::all();
        $route = DataRoute::all();
        $departure = MasterPort::all();
        $arrival = MasterPort::all();
        $deptTime = SchedulesTrip::all();
        $trip = SchedulesTrip::with(['departure.arrival.fastboat']);
        return view('fast-boat.availability.index', compact('trip', 'fastboat', 'company', 'schedule', 'route', 'departure', 'arrival', 'deptTime'));
    }

    public function fetchFastboat(Request $request) {
        $data = DataFastboat::where("fb_company", $request->cpn_id)->get(["fb_name", "fb_id"]);
        return response()->json(['fastboat' => $data]);
    }

    public function fetchSchedule(Request $request) {
        $data = SchedulesSchedule::where("sch_company", $request->cpn_id)->get(["sch_name", "sch_id"]);
        return response()->json(['schedule' => $data]);
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

    public function search(Request $request)
    {
        // Koleksi data untuk dropdown atau kebutuhan lainnya
        $company = DataCompany::all();
        $fastboat = DataFastboat::all();
        $schedule = SchedulesSchedule::all();
        $route = DataRoute::all();
        $departure = MasterPort::all();
        $arrival = MasterPort::all();
        $deptTime = SchedulesTrip::all();

        // Validasi input
        $validated = $request->validate([
            'company' => 'nullable|string',
            'fastboat' => 'nullable|string',
            'schedule' => 'nullable|string',
            'route' => 'nullable|string',
            'departure' => 'nullable|string',
            'arrival' => 'nullable|string',
            'dept_time' => 'nullable|string',
            'daterange' => 'nullable|regex:/\d{2}-\d{2}-\d{4} to \d{2}-\d{2}-\d{4}/', // Format date range
        ]);

        // Jika tidak ada input yang diisi, kembalikan ke halaman index dengan koleksi kosong
        if (
            !$request->filled('company') &&
            !$request->filled('fastboat') &&
            !$request->filled('schedule') &&
            !$request->filled('route') &&
            !$request->filled('departure') &&
            !$request->filled('arrival') &&
            !$request->filled('dept_time') &&
            !$request->filled('daterange')
        ) {

            // Kembalikan ke halaman index dengan data kosong
            return redirect()->route('availability.view')
                ->with('availabilities', collect()) // Mengirimkan koleksi kosong
                ->with(compact('fastboat', 'company', 'schedule', 'route', 'departure', 'arrival', 'deptTime'));
        }

        $query = FastboatAvailability::query();

        // Filter berdasarkan parameter yang ada
        if ($request->filled('company')) {
            $query->whereHas('trip.fastboat.company', function ($q) use ($request) {
                $q->where('cpn_id', $request->input('company'));
            });
        }

        if ($request->filled('fastboat')) {
            $query->whereHas('trip.fastboat', function ($q) use ($request) {
                $q->where('fb_id', $request->input('fastboat'));
            });
        }

        if ($request->filled('schedule')) {
            $query->whereHas('trip.schedule', function ($q) use ($request) {
                $q->where('sch_id', $request->input('schedule'));
            });
        }

        if ($request->filled('route')) {
            $query->whereHas('trip.route', function ($q) use ($request) {
                $routeParts = explode(' to ', $request->input('route'));
                $q->where('rt_dept_island', $routeParts[0])
                    ->where('rt_arrival_island', $routeParts[1]);
            });
        }

        if ($request->filled('departure')) {
            $query->whereHas('trip.departure', function ($q) use ($request) {
                $q->where('prt_name_en', $request->input('departure'));
            });
        }

        if ($request->filled('arrival')) {
            $query->whereHas('trip.arrival', function ($q) use ($request) {
                $q->where('prt_name_en', $request->input('arrival'));
            });
        }

        if ($request->filled('dept_time')) {
            $query->whereHas('trip', function ($q) use ($request) {
                $q->where('fbt_dept_time', $request->input('dept_time'));
            });
        }

        if ($request->filled('daterange')) {
            $dates = explode(' to ', $request->input('daterange'));
            // $startDate = \Carbon\Carbon::createFromFormat('d-m-Y', $dates[0]);
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = date('Y-m-d', strtotime($dates[1]));
            // $endDate = \Carbon\Carbon::createFromFormat('d-m-Y', $dates[1]);
            $query->whereBetween('fba_date', [$startDate, $endDate]);
        }

        // dd($startDate);
        $availabilities = $query->get();
        // dd($availabilities);

        // Redirect ke index dengan hasil pencarian
        return redirect()->route('availability.view')
            ->with('availabilities', $availabilities)
            ->with(compact('fastboat', 'company', 'schedule', 'route', 'departure', 'arrival', 'deptTime'))
            ->withInput(); // Menyimpan nilai input yang telah diisi
    }

    public function show($id)
    {
        $availability = FastboatAvailability::with(['trip.departure.island', 'trip.arrival.island', 'trip.fastboat'])->findOrFail($id);

        return response()->json($availability);
    }

    public function edit()
    {
        $availabilities = FastboatAvailability::all();
        return view('fast-boat.availability.edit', compact('availabilities'));
    }
    
    public function update(Request $request)
    {
        $availability = FastboatAvailability::find();
        // Validasi input
        $validated = $request->validate([
            'types' => 'required|array',
            'dates' => 'required|array',
            'availabilities' => 'required|array',
        ]);
    
        // Loop melalui setiap availability yang dipilih
        foreach ($request->availabilities as $availabilityId) {
            $availability = FastboatAvailability::findOrFail($availabilityId);
    
            // Update hanya field yang dipilih di update type
            if (in_array('price', $request->types)) {
                if ($request->has('fba_adult_nett')) {
                    $availability->fba_adult_nett = $request->fba_adult_nett;
                }
                if ($request->has('fba_child_nett')) {
                    $availability->fba_child_nett = $request->fba_child_nett;
                }
                if ($request->has('fba_adult_publish')) {
                    $availability->fba_adult_publish = $request->fba_adult_publish;
                }
                if ($request->has('fba_child_publish')) {
                    $availability->fba_child_publish = $request->fba_child_publish;
                }
                if ($request->has('fba_discount')) {
                    $availability->fba_discount = $request->fba_discount;
                }
            }
    
            if (in_array('stock', $request->types)) {
                if ($request->has('fba_stock')) {
                    $availability->fba_stock = $request->fba_stock;
                }
            }
    
            if (in_array('pax', $request->types)) {
                if ($request->has('fba_min_pax')) {
                    $availability->fba_min_pax = $request->fba_min_pax;
                }
            }
    
            if (in_array('shuttle-status', $request->types)) {
                if ($request->has('fba_shuttle_status')) {
                    $availability->fba_shuttle_status = $request->fba_shuttle_status;
                }
            }
    
            if (in_array('status', $request->types)) {
                if ($request->has('fba_status')) {
                    $availability->fba_status = $request->fba_status;
                }
            }
    
            if (in_array('info', $request->types)) {
                if ($request->has('fba_info')) {
                    $availability->fba_info = $request->fba_info;
                }
            }
    
            if (in_array('custom-time', $request->types)) {
                if ($request->has('fba_dept_time')) {
                    $availability->fba_dept_time = $request->fba_dept_time;
                }
                if ($request->has('fba_arriv_time')) {
                    $availability->fba_arriv_time = $request->fba_arriv_time;
                }
            }
    
            // Set field updated by
            $availability->fba_updated_by = auth()->id();
    
            // Simpan perubahan
            $availability->save();
        }
    
        // Tambahkan pesan toast sukses ke dalam session
        toast('Your data has been updated successfully!', 'success');
    
        // Redirect ke halaman view
        return redirect()->route('availability.view');
    }
    
}
