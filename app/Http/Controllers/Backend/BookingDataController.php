<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\DataFastboat;
use App\Models\DataRoute;
use App\Models\FastboatAvailability;
use App\Models\FastboatShuttle;
use App\Models\FastboatShuttleArea;
use App\Models\MasterPort;
use App\Models\FastboatTrip;
use App\Models\MasterCurrency;
use App\Models\MasterNationality;
use App\Models\MasterPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingDataController extends Controller
{
    public function index()
    {
        return view('booking.data.index');
    }

    public function add()
    {
        $currency = MasterCurrency::where('cy_status', '1')->get();
        $nationality = MasterNationality::all();
        $payment_method = MasterPaymentMethod::all();
        $contact = Contact::all();
        return view('booking.data.add', compact('currency', 'nationality', 'payment_method', 'contact'));
    }

    function generateOrderId()
    {
        // Ambil order terakhir dari database, jika ada
        $lastOrder = Contact::orderBy('ctc_id', 'desc')->first();

        if (!$lastOrder) {
            // Jika belum ada order, mulai dengan "AAAAA"
            return 'AAAAA';
        }

        // Ambil order_id terakhir
        $lastOrderId = $lastOrder->ctc_order_id;

        // Konversi order_id menjadi array karakter
        $characters = str_split($lastOrderId);

        // Loop dari belakang untuk increment karakter terakhir
        for ($i = count($characters) - 1; $i >= 0; $i--) {
            if ($characters[$i] == 'Z') {
                // Jika karakter terakhir adalah Z, ubah jadi A
                $characters[$i] = 'A';
            } else {
                // Jika bukan Z, tambahkan 1 ke karakter tersebut
                $characters[$i] = chr(ord($characters[$i]) + 1);
                break;
            }
        }

        // Jika semua karakter adalah 'Z', tambahkan satu karakter lagi
        if (implode('', $characters) == str_repeat('A', count($characters))) {
            array_unshift($characters, 'A');
        }

        // Gabungkan karakter menjadi string lagi
        return implode('', $characters);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();  // Memulai transaksi database

        try {

            dd($request);

            // Mendapatkan IP address
            $ipAddress = $request->ip(); // IP Publik pengguna

            // Jika berada di belakang proxy, coba ambil IP dari X-Forwarded-For
            if ($request->server('HTTP_X_FORWARDED_FOR')) {
                $ipAddress = $request->server('HTTP_X_FORWARDED_FOR');
            } else {
                $ipAddress = $request->ip();
            }

            // Simpan data kontak utama
            $contactData = new Contact();
            $contactData->ctc_order_id = $this->generateOrderId();
            $contactData->ctc_name = $request->ctc_name;
            $contactData->ctc_email = $request->ctc_email;
            $contactData->ctc_phone = $request->ctc_phone;
            $contactData->ctc_nationality = $request->ctc_nationality;
            $contactData->ctc_note = $request->ctc_note;
            $contactData->ctc_booking_date = Carbon::now()->toDateString();  // Mengambil tanggal saat ini
            $contactData->ctc_booking_time = Carbon::now()->toTimeString();
            $contactData->ctc_ip_address = $ipAddress;
            $contactData->ctc_browser = $request->header('User-Agent');
            $contactData->ctc_updated_by = Auth()->id();
            $contactData->ctc_created_by = Auth()->id();
            $contactData->save();

            // Ambil input data dewasa
            $adults = $request->input('adult_name');
            $adultAges = $request->input('adult_age');
            $adultGenders = $request->input('adult_gender');
            $adultNationalities = $request->input('adult_nationality');

            // Ambil input data anak-anak
            $children = $request->input('child_name');
            $childAges = $request->input('child_age');
            $childGenders = $request->input('child_gender');
            $childNationalities = $request->input('child_nationality');

            // Ambil input data bayi
            $infants = $request->input('infant_name');
            $infantAges = $request->input('infant_age');
            $infantGenders = $request->input('infant_gender');
            $infantNationalities = $request->input('infant_nationality');

            // Simpan data dewasa (Adult)
            foreach ($adults as $index => $adultName) {
                Adult::create([
                    'name' => $adultName,
                    'age' => $adultAges[$index],
                    'gender' => $adultGenders[$index],
                    'nationality' => $adultNationalities[$index],
                    'contact_id' => $contactData->id, // Referensi ke kontak utama
                ]);
            }

            // Simpan data anak-anak (Child), hanya jika data tersedia
            if ($children) {
                foreach ($children as $index => $childName) {
                    if ($childName) { // Pastikan ada input nama anak
                        Child::create([
                            'name' => $childName,
                            'age' => $childAges[$index],
                            'gender' => $childGenders[$index],
                            'nationality' => $childNationalities[$index],
                            'contact_id' => $contactData->id, // Referensi ke kontak utama
                        ]);
                    }
                }
            }

            // Simpan data bayi (Infant), hanya jika data tersedia
            if ($infants) {
                foreach ($infants as $index => $infantName) {
                    if ($infantName) { // Pastikan ada input nama bayi
                        Infant::create([
                            'name' => $infantName,
                            'age' => $infantAges[$index],
                            'gender' => $infantGenders[$index],
                            'nationality' => $infantNationalities[$index],
                            'contact_id' => $contactData->id, // Referensi ke kontak utama
                        ]);
                    }
                }
            }

            // Commit transaksi jika semua proses berhasil
            DB::commit();

            return redirect()->route('data.view')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            // Rollback semua perubahan jika terjadi error
            DB::rollBack();

            // Lakukan logging atau beri tahu pengguna bahwa terjadi kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDate = $request->input('fbo_trip_date');
            $departurePort = $request->input('fbo_departure_port');
            $arrivalPort = $request->input('fbo_arrival_port');
            $fastBoat = $request->input('fbo_fast_boat');
            $timeDept = $request->input('time_dept');
            $adultCount = $request->input('fbo_adult', 1); // Default 1 dewasa
            $childCount = $request->input('fbo_child', 0); // Default 0 anak-anak
            $area = $request->input('fbo_area'); // Tambahkan input area

            // Total customer
            $totalCustomer = $adultCount + $childCount;

            // Query untuk FastboatAvailability dengan pengecekan stok
            $availabilityQuery = FastboatAvailability::whereHas('trip.departure', function ($query) use ($departurePort) {
                $query->where('prt_name_en', $departurePort);
            })
                ->whereHas('trip.arrival', function ($query) use ($arrivalPort) {
                    $query->where('prt_name_en', $arrivalPort);
                })
                ->whereHas('trip.fastboat', function ($query) use ($fastBoat) {
                    $query->where('fb_name', $fastBoat);
                })
                ->where('fba_date', $tripDate)
                // Pengecekan stok harus lebih besar dari total customer
                ->where('fba_stock', '>', $totalCustomer);

            // Filter berdasarkan area, jika ada
            if ($area) {
                $availabilityQuery->whereHas('trip.area', function ($query) use ($area) {
                    $query->where('area_name', $area);
                });
            }

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
            $shuttleType = null;
            $shuttleOption = null;
            $pickupAreas = [];
            $dropoffAreas = [];
            $shuttleAddresses = []; 

            if (!$availability->isEmpty()) {
                $trip = $availability->first()->trip;
                $shuttleType = $trip->fbt_shuttle_type;
                $shuttleOption = $trip->fbt_shuttle_option;
            
                foreach ($availability as $avail) {
                    if ($shuttleType && in_array($shuttleType, ['Private', 'Sharing'])) {
                        $trip_id = $avail->trip->fbt_id;
                        $Areas = FastboatShuttle::where('s_trip', $trip_id)->get();

                        if (!$availability->isEmpty()) {
                            $shuttleType = $availability->first()->trip->fbt_shuttle_type;
                
                            foreach ($availability as $avail) {
                                $trip_id = $avail->trip->fbt_id;
                                $shuttles = FastboatShuttle::where('s_trip', $trip_id)->get();
                
                                foreach ($shuttles as $shuttle) {
                                    $shuttleAddresses[] = [
                                        'area_id' => $shuttle->area->sa_id,
                                        'area_name' => $shuttle->area->sa_name,
                                        'pickup_meeting_point' => $shuttle->s_meeting_point ?? '',  // Ambil pickup meeting point
                                        'dropoff_meeting_point' => $shuttle->s_meeting_point ?? '', // Ambil dropoff meeting point
                                    ];
                                }
                            }
                        }
            
                        if ($shuttleOption === 'pickup') {
                            // For pickup option
                            $pickupAreas = $Areas->isNotEmpty() ? $Areas->map(function ($value) {
                                return [
                                    'id' => $value->area->sa_id,
                                    'name' => $value->area->sa_name
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
                                    'name' => $value->area->sa_name
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

            // Jika tidak ada data di FastboatAvailability, ambil dari trip saja
            if ($availability->isEmpty()) {
                $tripQuery = FastboatTrip::whereHas('departure', function ($query) use ($departurePort) {
                    $query->where('prt_name_en', $departurePort);
                })
                    ->whereHas('arrival', function ($query) use ($arrivalPort) {
                        $query->where('prt_name_en', $arrivalPort);
                    })
                    ->whereHas('fastboat', function ($query) use ($fastBoat) {
                        $query->where('fb_name', $fastBoat);
                    })
                    ->where('fbt_dept_time', $timeDept)
                    ->whereDate('fbt_fbo_trip_date', $tripDate)
                    ->where('fbt_stock', '>', $totalCustomer);

                if ($area) {
                    $tripQuery->whereHas('area', function ($query) use ($area) {
                        $query->where('area_name', $area);
                    });
                }

                $trips = $tripQuery->get();

                if ($trips->isEmpty()) {
                    return response()->json(['message' => 'No availability found'], 404);
                }

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

            $html = '';
            $cardTitle = '';
            $adultPublishTotal = 0;
            $childPublishTotal = 0;
            $discountPerPerson = 0;
            $shuttleAvailable = false; // Inisialisasi shuttle tidak tersedia

            foreach ($availability as $avail) {
                $trip = $avail->trip;
                $deptTime = $avail->fba_dept_time ?? $trip->fbt_dept_time;
                $cardTitle = '<center>' . $trip->fastboat->fb_name . ' (' .
                    $trip->departure->prt_code . ' -> ' . $trip->arrival->prt_code . ' ' .
                    date('H:i', strtotime($deptTime)) . ')' . '</center>';

                $html .= '<tr>';
                // Kolom untuk adult publish dengan input
                $html .= '<td><center>';
                $html .= '<input type="hidden" name="availability[' . $avail->fba_id . '][adult_publish]" value="' . $avail->fba_adult_publish . '">';
                $html .= number_format($avail->fba_adult_publish ?? 0, 0, ',', '.');
                $html .= '</center></td>';

                // Kolom untuk child publish dengan input
                $html .= '<td><center>';
                $html .= '<input type="hidden" name="availability[' . $avail->fba_id . '][child_publish]" value="' . $avail->fba_child_publish . '">';
                $html .= number_format($avail->fba_child_publish ?? 0, 0, ',', '.');
                $html .= '</center></td>';

                $html .= '<td><center>' . number_format($avail->fba_adult_nett ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_child_nett ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '<td><center>' . number_format($avail->fba_discount ?? 0, 0, ',', '.') . '</center></td>';
                $html .= '</tr>';

                $adultPublishTotal += $avail->fba_adult_publish ?? 0;
                $childPublishTotal += $avail->fba_child_publish ?? 0;

                $discountPerPerson = $avail->fba_discount ?? 0;

                // Cek apakah shuttle tersedia
                if (!empty($trip->fbt_shuttle_type)) {
                    $shuttleAvailable = true;
                }
            }

            // Tentukan apakah checkbox harus ditampilkan berdasarkan tipe shuttle
            $showShuttleCheckbox = in_array($shuttleAvailable, ['Private', 'Sharing']);
            // dd($pickupAreas);
            // Kembalikan response termasuk shuttle availability
            return response()->json([
                'html' => $html,
                'card_title' => $cardTitle,
                'adult_publish' => number_format($adultPublishTotal, 0, ',', '.'),
                'child_publish' => number_format($childPublishTotal, 0, ',', '.'),
                'discount' => number_format($discountPerPerson, 0, ',', '.'),
                'show_shuttle_checkbox' => $showShuttleCheckbox,
                'shuttle_type' => $shuttleType,
                'shuttle_option' => $shuttleOption,
                'pickup_areas' => $pickupAreas,
                'dropoff_areas' => $dropoffAreas,
                'shuttle_addresses' => $shuttleAddresses, 
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getFilteredData(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDate = $request->input('fbo_trip_date');
            $departurePort = $request->input('fbo_departure_port');
            $arrivalPort = $request->input('fbo_arrival_port');
            $fastBoat = $request->input('fbo_fast_boat');
            $adultCount = $request->input('fbo_adult', 1); // Default 1 dewasa
            $childCount = $request->input('fbo_child', 0); // Default 0 anak-anak

            // Hitung total customer (dewasa + anak-anak)
            $totalCustomer = $adultCount + $childCount;

            // Filter berdasarkan tanggal
            $query = FastboatAvailability::where('fba_date', $tripDate)
                ->where('fba_stock', '>', $totalCustomer); // Tambahkan filter stok

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

            // Jika tidak ada availability, tidak perlu melanjutkan
            if ($availabilities->isEmpty()) {
                return response()->json([
                    'fbo_departure_ports' => [],
                    'fbo_arrival_ports' => [],
                    'fbo_fast_boats' => [],
                    'time_depts' => [],
                    'show_shuttle_checkbox' => false, // Tidak ada checkbox
                ]);
            }

            // Format data untuk dropdown
            $departurePorts = [];
            $arrivalPorts = [];
            $fastBoats = [];
            $timeDepts = [];
            $showShuttleCheckbox = false;

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

                // Cek apakah shuttle type adalah 'Private' atau 'Sharing'
                if ($trip->fbt_shuttle_type === 'Private' || $trip->fbt_shuttle_type === 'Sharing') {
                    $showShuttleCheckbox = true; // Tampilkan checkbox jika shuttle tersedia
                }
            }

            // Return data untuk setiap dropdown dan flag shuttle checkbox
            return response()->json([
                'fbo_departure_ports' => $departurePorts,
                'fbo_arrival_ports' => $arrivalPorts,
                'fbo_fast_boats' => $fastBoats,
                'time_depts' => $timeDepts,
                'show_shuttle_checkbox' => $showShuttleCheckbox,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function searchReturn(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDateReturn = $request->input('trip_return_date');
            $departurePortReturn = $request->input('departure_return_port');
            $arrivalPortReturn = $request->input('arrival_return_port');
            $fastBoatReturn = $request->input('fbo_fast_boat_return');
            $timeDeptReturn = $request->input('time_dept_return');
            $adultCountReturn = $request->input('fbo_adult', 1); // Default 1 dewasa
            $childCountReturn = $request->input('fbo_child', 0); // Default 0 anak-anak
            $areaReturn = $request->input('fbo_area'); // Tambahkan input area

            // Total customer
            $totalCustomerReturn = $adultCountReturn + $childCountReturn;

            // Query untuk FastboatAvailability dengan pengecekan stok
            $availabilityQuery = FastboatAvailability::whereHas('trip.departure', function ($query) use ($departurePortReturn) {
                $query->where('prt_name_en', $departurePortReturn);
            })
                ->whereHas('trip.arrival', function ($query) use ($arrivalPortReturn) {
                    $query->where('prt_name_en', $arrivalPortReturn);
                })
                ->whereHas('trip.fastboat', function ($query) use ($fastBoatReturn) {
                    $query->where('fb_name', $fastBoatReturn);
                })
                ->where('fba_date', $tripDateReturn)
                ->where('fba_stock', '>', $totalCustomerReturn);

            // Filter berdasarkan area, jika ada
            if ($areaReturn) {
                $availabilityQuery->whereHas('trip.area', function ($query) use ($areaReturn) {
                    $query->where('area_name', $areaReturn);
                });
            }

            // Filter berdasarkan timeDept, jika ada
            if ($timeDeptReturn) {
                $availabilityQuery->where(function ($query) use ($timeDeptReturn) {
                    $query->where('fba_dept_time', $timeDeptReturn)
                        ->orWhereHas('trip', function ($q) use ($timeDeptReturn) {
                            $q->where('fbt_dept_time', $timeDeptReturn);
                        });
                });
            }

            // Ambil data FastboatAvailability
            $availability = $availabilityQuery->get();
            $shuttleType = null;
            $shuttleOption = null;
            $pickupAreas = [];
            $dropoffAreas = [];

            if (!$availability->isEmpty()) {
                $trip = $availability->first()->trip;
                $shuttleTypeReturn = $trip->fbt_shuttle_type;
                $shuttleOptionReturn = $trip->fbt_shuttle_option;
            
                foreach ($availability as $avail) {
                    if ($shuttleTypeReturn && in_array($shuttleTypeReturn, ['Private', 'Sharing'])) {
                        $trip_id = $avail->trip->fbt_id;
                        $Areas = FastboatShuttle::where('s_trip', $trip_id)->get();
            
                        if ($shuttleOptionReturn === 'pickup') {
                            // For pickup option
                            $pickupAreas = $Areas->isNotEmpty() ? $Areas->map(function ($value) {
                                return [
                                    'id' => $value->area->sa_id,
                                    'name' => $value->area->sa_name
                                ];
                            })->toArray() : [];
            
                            $dropoffAreas = FastboatShuttleArea::all()->map(function ($area) {
                                return [
                                    'id' => $area->sa_id,
                                    'name' => $area->sa_name,
                                ];
                            })->toArray();
                        } elseif ($shuttleOptionReturn === 'drop') {
                            // For drop option
                            $dropoffAreas = $Areas->isNotEmpty() ? $Areas->map(function ($value) {
                                return [
                                    'id' => $value->area->sa_id,
                                    'name' => $value->area->sa_name
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
            // dd($dropoffAreas);

            // Jika tidak ada data di FastboatAvailability, ambil dari trip saja
            if ($availability->isEmpty()) {
                $tripQuery = FastboatTrip::whereHas('departure', function ($query) use ($departurePortReturn) {
                    $query->where('prt_name_en', $departurePortReturn);
                })
                    ->whereHas('arrival', function ($query) use ($arrivalPortReturn) {
                        $query->where('prt_name_en', $arrivalPortReturn);
                    })
                    ->whereHas('fastboat', function ($query) use ($fastBoatReturn) {
                        $query->where('fb_name', $fastBoatReturn);
                    })
                    ->where('fbt_dept_time', $timeDeptReturn)
                    ->whereDate('fbt_fbo_trip_date', $tripDateReturn)
                    ->where('fbt_stock', '>', $totalCustomerReturn);

                if ($areaReturn) {
                    $tripQuery->whereHas('area', function ($query) use ($areaReturn) {
                        $query->where('area_name', $areaReturn);
                    });
                }

                $trips = $tripQuery->get();

                if ($trips->isEmpty()) {
                    return response()->json(['message' => 'No availability found'], 404);
                }

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

            $htmlReturn = '';
            $cardTitleReturn = '';
            $adultPublishTotalReturn = 0;
            $childPublishTotalReturn = 0;
            $discountPerPersonReturn = 0;
            $shuttleAvailableReturn = false;

            foreach ($availability as $avail) {
                $trip = $avail->trip;
                $deptTimeReturn = $avail->fba_dept_time ?? $trip->fbt_dept_time;
                $cardTitleReturn = '<center>' . $trip->fastboat->fb_name . ' (' .
                    $trip->departure->prt_code . ' -> ' . $trip->arrival->prt_code . ' ' .
                    date('H:i', strtotime($deptTimeReturn)) . ')</center>';

                $htmlReturn .= '<tr>';
                $htmlReturn .= '<td><center>' . number_format($avail->fba_adult_publish ?? 0, 0, ',', '.') . '</center></td>';
                $htmlReturn .= '<td><center>' . number_format($avail->fba_child_publish ?? 0, 0, ',', '.') . '</center></td>';
                $htmlReturn .= '<td><center>' . number_format($avail->fba_adult_nett ?? 0, 0, ',', '.') . '</center></td>';
                $htmlReturn .= '<td><center>' . number_format($avail->fba_child_nett ?? 0, 0, ',', '.') . '</center></td>';
                $htmlReturn .= '<td><center>' . number_format($avail->fba_discount ?? 0, 0, ',', '.') . '</center></td>';
                $htmlReturn .= '</tr>';

                $adultPublishTotalReturn += $avail->fba_adult_publish ?? 0;
                $childPublishTotalReturn += $avail->fba_child_publish ?? 0;
                $discountPerPersonReturn = $avail->fba_discount ?? 0;

                if (!empty($trip->fbt_shuttle_type)) {
                    $shuttleAvailableReturn = true;
                }
            }

            $showShuttleCheckboxReturn = in_array($shuttleTypeReturn, ['Private', 'Sharing']);

            return response()->json([
                'htmlReturn' => $htmlReturn,
                'card_return_title' => $cardTitleReturn,
                'adult_return_publish' => number_format($adultPublishTotalReturn, 0, ',', '.'),
                'child_return_publish' => number_format($childPublishTotalReturn, 0, ',', '.'),
                'discount_return' => number_format($discountPerPersonReturn, 0, ',', '.'),
                'show_shuttle_checkbox_return' => $showShuttleCheckboxReturn,
                'shuttle_type_return' => $shuttleType,
                'shuttle_option_return' => $shuttleOption,
                'pickup_areas_return' => $pickupAreas,
                'dropoff_areas_return' => $dropoffAreas,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getFilteredDataReturn(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDate = $request->input('trip_return_date');
            $departurePort = $request->input('departure_return_port');
            $arrivalPort = $request->input('arrival_return_port');
            $fastBoat = $request->input('fbo_fast_boat_return');
            $adultCount = $request->input('fbo_adult', 1); // Default 1 dewasa
            $childCount = $request->input('fbo_child', 0); // Default 0 anak-anak

            // Hitung total customer (dewasa + anak-anak)
            $totalCustomer = $adultCount + $childCount;

            // Filter berdasarkan tanggal dan stok
            $query = FastboatAvailability::where('fba_date', $tripDate)
                ->where('fba_stock', '>', $totalCustomer); // Tambahkan filter stok

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

            // Jika tidak ada availability, tidak perlu melanjutkan
            if ($availabilities->isEmpty()) {
                return response()->json([
                    'departure_return_ports' => [],
                    'arrival_return_ports' => [],
                    'fbo_fast_boats_return' => [],
                    'time_depts_return' => [],
                    'show_shuttle_checkbox_return' => false, // Tidak ada checkbox
                ]);
            }

            // Format data untuk dropdown
            $departurePorts = [];
            $arrivalPorts = [];
            $fastBoats = [];
            $timeDepts = [];
            $showShuttleCheckbox = false;

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

                // Cek apakah shuttle type adalah 'Private' atau 'Sharing'
                if ($trip->fbt_shuttle_type === 'Private' || $trip->fbt_shuttle_type === 'Sharing') {
                    $showShuttleCheckbox = true; // Tampilkan checkbox jika shuttle tersedia
                }
            }

            // Return data untuk setiap dropdown dan flag shuttle checkbox
            return response()->json([
                'departure_return_ports' => $departurePorts,
                'arrival_return_ports' => $arrivalPorts,
                'fbo_fast_boats_return' => $fastBoats,
                'time_depts_return' => $timeDepts,
                'show_shuttle_checkbox_return' => $showShuttleCheckbox,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
