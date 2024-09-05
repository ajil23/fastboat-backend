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
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class FastboatAvailabilityController extends Controller
{
    // this function is for view all data from fastboat table
    public function index(Request $request)
    {
        $company = DataCompany::get(["cpn_name", "cpn_id"]);
        $fastboat = DataFastboat::all();
        $schedule = SchedulesSchedule::all();
        $route = DataRoute::all();
        $departure = MasterPort::all();
        $arrival = MasterPort::all();
        $deptTime = SchedulesTrip::all();

        $query = FastboatAvailability::query();

        // Only process the search if at least one filter is provided
        $hasFilters = false;

        // Filter based on the provided parameters
        if ($request->filled('company')) {
            $query->whereHas('trip.fastboat.company', function ($q) use ($request) {
                $q->where('cpn_id', $request->input('company'));
            });
            $hasFilters = true;
        }

        if ($request->filled('fastboat')) {
            $query->whereHas('trip.fastboat', function ($q) use ($request) {
                $q->where('fb_id', $request->input('fastboat'));
            });
            $hasFilters = true;
        }

        if ($request->filled('schedule')) {
            $query->whereHas('trip.schedule', function ($q) use ($request) {
                $q->where('sch_id', $request->input('schedule'));
            });
            $hasFilters = true;
        }

        if ($request->filled('route')) {
            $query->whereHas('trip.route', function ($q) use ($request) {
                $routeParts = explode(' to ', $request->input('route'));
                $q->where('rt_dept_island', $routeParts[0])
                    ->where('rt_arrival_island', $routeParts[1]);
            });
            $hasFilters = true;
        }

        if ($request->filled('departure')) {
            $query->whereHas('trip.departure', function ($q) use ($request) {
                $q->where('prt_name_en', $request->input('departure'));
            });
            $hasFilters = true;
        }

        if ($request->filled('arrival')) {
            $query->whereHas('trip.arrival', function ($q) use ($request) {
                $q->where('prt_name_en', $request->input('arrival'));
            });
            $hasFilters = true;
        }

        if ($request->filled('dept_time')) {
            $query->whereHas('trip', function ($q) use ($request) {
                $q->where('fbt_dept_time', $request->input('dept_time'));
            });
            $hasFilters = true;
        }

        if ($request->filled('daterange')) {
            $dates = explode(' to ', $request->input('daterange'));
            $startDate = date('Y-m-d', strtotime($dates[0]));
            $endDate = count($dates) == 1 ? $startDate : date('Y-m-d', strtotime($dates[1]));
            $query->whereBetween('fba_date', [$startDate, $endDate]);
            $hasFilters = true;
        }

        // Fetch filtered data
        $availabilities = $query->get();

        // Save the current URL in the session to remember the filters
        $request->session()->put('previous_url', $request->fullUrl());

        // If no filters, set $availabilities to an empty collection
        $availabilities = $hasFilters ? $query->get() : collect([]);

        return view('fast-boat.availability.index', compact('availabilities', 'fastboat', 'company', 'schedule', 'route', 'departure', 'arrival', 'deptTime'))
            ->withInput($request->all());
    }

    public function fetchFastboat(Request $request)
    {
        $data = DataFastboat::where("fb_company", $request->cpn_id)->get(["fb_name", "fb_id"]);
        return response()->json(['fastboat' => $data]);
    }

    public function fetchSchedule(Request $request)
    {
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
                $availabilityData->fba_min_pax = $request->fba_min_pax;
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
                        $availabilityData->fba_min_pax = $templateAvailability->fba_min_pax;
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

    public function show($id)
    {
        $availability = FastboatAvailability::with(['trip.departure.island', 'trip.arrival.island', 'trip.fastboat'])->findOrFail($id);

        return response()->json($availability);
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'select_availability' => 'required|array',
            'selected_fields' => 'required|array',
        ]);

        // Cek apakah validasi gagal
        if ($validator->fails()) {
            // Menambahkan pesan toast ke dalam session
            toast('Validation failed! Please check your input.', 'error');
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Dapatkan ID yang dipilih dari halaman sebelumnya
        $selectedAvailabilityIds = $request->input('select_availability', []);
        $selectedFields = $request->input('selected_fields', []);

        // Cek apakah ada data yang diterima
        if (empty($selectedAvailabilityIds) || empty($selectedFields)) {
            return redirect()->back()->with('error', 'Please select at least one availability and one field.');
        }

        // Ambil semua data availability berdasarkan ID yang dipilih
        $availabilities = FastboatAvailability::whereIn('fba_id', $selectedAvailabilityIds)->get();

        // Kirim data ke view
        return view('fast-boat.availability.edit', compact('availabilities', 'selectedFields'));
    }

    public function update(Request $request)
    {
        // Ambil semua ID yang dipilih dari request
        $selectedAvailabilityIds = explode(',', $request->input('selected_availability_ids')[0]);

        // Ambil data yang diinput dari form
        $data = $request->except('selected_availability_ids');

        // Update setiap availability yang dipilih
        foreach ($selectedAvailabilityIds as $availabilityId) {
            $availability = FastboatAvailability::find($availabilityId);

            if ($availability) {
                // Lakukan update pada setiap field yang diinputkan
                if (isset($data['fba_adult_nett'])) {
                    $availability->fba_adult_nett = str_replace('.', '', $data['fba_adult_nett']);
                }
                if (isset($data['fba_child_nett'])) {
                    $availability->fba_child_nett = str_replace('.', '', $data['fba_child_nett']);
                }
                if (isset($data['fba_adult_publish'])) {
                    $availability->fba_adult_publish = str_replace('.', '', $data['fba_adult_publish']);
                }
                if (isset($data['fba_child_publish'])) {
                    $availability->fba_child_publish = str_replace('.', '', $data['fba_child_publish']);
                }
                if (isset($data['fba_discount'])) {
                    $availability->fba_discount = str_replace('.', '', $data['fba_discount']);
                }
                if (isset($data['fba_stock'])) {
                    $availability->fba_stock = $data['fba_stock'];
                }
                if (isset($data['fba_min_pax'])) {
                    $availability->fba_min_pax = $data['fba_min_pax'];
                }
                if (isset($data['fba_shuttle_status'])) {
                    $availability->fba_shuttle_status = $data['fba_shuttle_status'];
                }
                if (isset($data['fba_status'])) {
                    $availability->fba_status = $data['fba_status'];
                }
                if (isset($data['fba_info'])) {
                    $availability->fba_info = $data['fba_info'];
                }
                if (isset($data['fba_dept_time'])) {
                    $availability->fba_dept_time = $data['fba_dept_time'];
                }
                if (isset($data['fba_arriv_time'])) {
                    $availability->fba_arriv_time = $data['fba_arriv_time'];
                }

                // Set field updated_by
                $availability->fba_updated_by = auth()->id();

                // Simpan perubahan
                $availability->save();
            }
        }

        // Ambil URL sebelumnya dari session
        $previousUrl = session()->get('previous_url', route('availability.view'));

        // Redirect ke halaman index dengan URL yang sama setelah data di-update
        return redirect($previousUrl)->with('status', 'Data updated successfully');
    }
}
