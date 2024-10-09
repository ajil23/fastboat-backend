<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
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
        dd($request);

        DB::beginTransaction();  // Memulai transaksi database

        try {
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
            // $contactData->save();

            // Menentukan tipe perjalanan, apakah sekali jalan atau pulang pergi
            if ($request->has('switch')) {
                $departSuffix = 'Y'; // Kode pergi
                $returnSuffix = 'Z'; // Kode pulang
                
                $keys = array_keys($request->input('fbo_availability_id')); // Memecah array untuk mengambil nilai id dari availability
                
                $bookingDataDepart = new BookingData();
                $bookingDataDepart->fbo_order_id = $contactData->ctc_order_id;
                $bookingDataDepart->fbo_transaction_id = $request->fbo_transaction_id;
                $bookingDataDepart->fbo_booking_id = 'F' . $contactData->ctc_order_id . $departSuffix;
                $bookingDataDepart->fbo_availability_id = $keys[0];
                $bookingDataDepart->fbo_trip_id;
                $bookingDataDepart->fbo_transaction_status;
                $bookingDataDepart->fbo_currency = $request -> fbo_currency;
                $bookingDataDepart->fbo_payment_method;
                $bookingDataDepart->fbo_payment_status;
                $bookingDataDepart->fbo_trip_date;
                $bookingDataDepart->fbo_adult_nett;
                $bookingDataDepart->fbo_child_nett;
                $bookingDataDepart->fbo_total_nett;
                $bookingDataDepart->fbo_adult_publish;
                $bookingDataDepart->fbo_child_publish;
                $bookingDataDepart->fbo_total_publish;
                $bookingDataDepart->fbo_adult_currency;
                $bookingDataDepart->fbo_child_currency;
                $bookingDataDepart->fbo_total_currency;
                $bookingDataDepart->fbo_kurs;
                $bookingDataDepart->fbo_discount;
                $bookingDataDepart->fbo_price_cut;
                $bookingDataDepart->fbo_discount_total;
                $bookingDataDepart->fbo_refund;
                $bookingDataDepart->fbo_end_total;
                $bookingDataDepart->fbo_end_total_currency;
                $bookingDataDepart->fbo_profit;
                $bookingDataDepart->fbo_passenger;
                $bookingDataDepart->fbo_adult;
                $bookingDataDepart->fbo_child;
                $bookingDataDepart->fbo_infant;
                $bookingDataDepart->fbo_company;
                $bookingDataDepart->fbo_fast_boat;
                $bookingDataDepart->fbo_departure_island;
                $bookingDataDepart->fbo_departure_port;
                $bookingDataDepart->fbo_departure_time;
                $bookingDataDepart->fbo_arrival_island;
                $bookingDataDepart->fbo_arrival_port;
                $bookingDataDepart->fbo_arrival_time;
                $bookingDataDepart->fbo_checking_point;
                $bookingDataDepart->fbo_mail_admin;
                $bookingDataDepart->fbo_mail_client;
                $bookingDataDepart->fbo_pickup;
                $bookingDataDepart->fbo_dropoff;
                $bookingDataDepart->fbo_specific_pickup;
                $bookingDataDepart->fbo_specific_dropoff;
                $bookingDataDepart->fbo_contact_pickup;
                $bookingDataDepart->fbo_contact_dropoff;
                $bookingDataDepart->fbo_log;
                $bookingDataDepart->fbo_source;
                $bookingDataDepart->fbo_updated_by;
                // $bookingDataDepart->save();
                
                $keys = array_keys($request->input('fbo_availability_id_return')); // Memecah array untuk mengambil nilai id dari availability
                $bookingDataReturn = new BookingData();
                $bookingDataReturn->fbo_order_id = $contactData->ctc_order_id;
                $bookingDataReturn->fbo_transaction_id = $request->fbo_transaction_id;
                $bookingDataReturn->fbo_booking_id = 'F' . $contactData->ctc_order_id . $returnSuffix;
                $bookingDataReturn->fbo_availability_id = $keys[0];
                $bookingDataReturn->fbo_trip_id;
                $bookingDataReturn->fbo_transaction_status;
                $bookingDataReturn->fbo_currency;
                $bookingDataReturn->fbo_payment_method;
                $bookingDataReturn->fbo_payment_status;
                $bookingDataReturn->fbo_trip_date;
                $bookingDataReturn->fbo_adult_nett;
                $bookingDataReturn->fbo_child_nett;
                $bookingDataReturn->fbo_total_nett;
                $bookingDataReturn->fbo_adult_publish;
                $bookingDataReturn->fbo_child_publish;
                $bookingDataReturn->fbo_total_publish;
                $bookingDataReturn->fbo_adult_currency;
                $bookingDataReturn->fbo_child_currency;
                $bookingDataReturn->fbo_total_currency;
                $bookingDataReturn->fbo_kurs;
                $bookingDataReturn->fbo_discount;
                $bookingDataReturn->fbo_price_cut;
                $bookingDataReturn->fbo_discount_total;
                $bookingDataReturn->fbo_refund;
                $bookingDataReturn->fbo_end_total;
                $bookingDataReturn->fbo_end_total_currency;
                $bookingDataReturn->fbo_profit;
                $bookingDataReturn->fbo_passenger;
                $bookingDataReturn->fbo_adult;
                $bookingDataReturn->fbo_child;
                $bookingDataReturn->fbo_infant;
                $bookingDataReturn->fbo_company;
                $bookingDataReturn->fbo_fast_boat;
                $bookingDataReturn->fbo_departure_island;
                $bookingDataReturn->fbo_departure_port;
                $bookingDataReturn->fbo_departure_time;
                $bookingDataReturn->fbo_arrival_island;
                $bookingDataReturn->fbo_arrival_port;
                $bookingDataReturn->fbo_arrival_time;
                $bookingDataReturn->fbo_checking_point;
                $bookingDataReturn->fbo_mail_admin;
                $bookingDataReturn->fbo_mail_client;
                $bookingDataReturn->fbo_pickup;
                $bookingDataReturn->fbo_dropoff;
                $bookingDataReturn->fbo_specific_pickup;
                $bookingDataReturn->fbo_specific_dropoff;
                $bookingDataReturn->fbo_contact_pickup;
                $bookingDataReturn->fbo_contact_dropoff;
                $bookingDataReturn->fbo_log;
                $bookingDataReturn->fbo_source;
                $bookingDataReturn->fbo_updated_by;
                // $bookingDataReturn->save();
            } else {
                $singleSuffix = 'X'; // Kode pergi
                $keys = array_keys($request->input('fbo_availability_id')); // Memecah array untuk mengambil nilai id dari availability

                $bookingDataSingle = new BookingData();
                $bookingDataSingle->fbo_order_id = $contactData->ctc_order_id;
                $bookingDataSingle->fbo_transaction_id = $request->fbo_transaction_id;
                $bookingDataSingle->fbo_booking_id = 'F' . $contactData->ctc_order_id . $singleSuffix;
                $bookingDataSingle->fbo_availability_id = $keys[0];
                // $bookingDataSingle->save();
            }

            // dd($bookingDataDepart);
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
            $timeDept = $request->input('fbo_departure_time');
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
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_adult_publish]" value="' . $avail->fba_adult_publish . '">';
                $html .= number_format($avail->fba_adult_publish ?? 0, 0, ',', '.');
                $html .= '</center></td>';

                // Kolom untuk child publish dengan input
                $html .= '<td><center>';
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_child_publish]" value="' . $avail->fba_child_publish . '">';
                $html .= number_format($avail->fba_child_publish ?? 0, 0, ',', '.');
                $html .= '</center></td>';

                // Perhitungan Total Publish (adult publish * adult count) + (child publish * child count)
                $total_publish = ($avail->fba_adult_publish ?? 0) * $adultCount + ($avail->fba_child_publish ?? 0) * $childCount;

                // Kolom untuk total publish
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_total_publish]" value="' . $total_publish . '">';
                $html .= number_format($total_publish, 0, ',', '.');
                
                // Kolom untuk adult nett dengan input
                $html .= '<td><center>';
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_adult_nett]" value="' . $avail->fba_adult_nett . '">';
                $html .= number_format($avail->fba_adult_nett ?? 0, 0, ',', '.');
                $html .= '</center></td>';

                // Kolom untuk child nett dengan input
                $html .= '<td><center>';
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_child_nett]" value="' . $avail->fba_child_nett . '">';
                $html .= number_format($avail->fba_child_nett ?? 0, 0, ',', '.');
                $html .= '</center></td>';

                // Perhitungan Total Nett (Adult Nett * Adult Count + Child Nett * Child Count)
                $total_nett = (($avail->fba_adult_nett ?? 0) * $adultCount) + (($avail->fba_child_nett ?? 0) * $childCount);

                // Kolom untuk total nett
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_total_nett]" value="' . $total_nett . '">';
                $html .= number_format($total_nett, 0, ',', '.');

                // Kolom untuk discount dengan input
                $html .= '<td><center>';
                $html .= '<input type="hidden" name="fbo_availability_id[' . $avail->fba_id . '][fbo_discount]" value="' . $avail->fba_discount . '">';
                $html .= number_format($avail->fba_discount ?? 0, 0, ',', '.');
                $html .= '</center></td>';

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
                'price_adult' => number_format($adultPublishTotal, 0, ',', '.'),
                'price_child' => number_format($childPublishTotal, 0, ',', '.'),
                'discount' => number_format($discountPerPerson, 0, ',', '.'),
                'show_shuttle_checkbox' => $showShuttleCheckbox,
                'shuttle_type' => $shuttleType,
                'shuttle_option' => $shuttleOption,
                'fbo_pickups' => $pickupAreas,
                'fbo_dropoffs' => $dropoffAreas,
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
                    'fbo_departure_times' => [],
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
                'fbo_departure_times' => $timeDepts,
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
            $timeDeptReturn = $request->input('fbo_departure_time_return');
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
            $shuttleAddressesReturn = [];

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
                                    'name' => $value->area->sa_name,
                                    'pickup_meeting_point_return' => $value->s_meeting_point ?? '',
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
                                    'name' => $value->area->sa_name,
                                    'dropoff_meeting_point_return' => $value->s_meeting_point ?? '',
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
            // dd($pickupAreas);

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
                // Kolom untuk adult publish dengan input
                $htmlReturn .= '<td><center>';
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_adult_publish]" value="' . $avail->fba_adult_publish . '">';
                $htmlReturn .= number_format($avail->fba_adult_publish ?? 0, 0, ',', '.');
                $htmlReturn .= '</center></td>';

                // Kolom untuk child publish dengan input
                $htmlReturn .= '<td><center>';
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_child_publish]" value="' . $avail->fba_child_publish . '">';
                $htmlReturn .= number_format($avail->fba_child_publish ?? 0, 0, ',', '.');
                $htmlReturn .= '</center></td>';

                // Perhitungan Total Publish (adult publish * adult count) + (child publish * child count)
                $total_publish = ($avail->fba_adult_publish ?? 0) * $adultCountReturn + ($avail->fba_child_publish ?? 0) * $childCountReturn;

                // Kolom untuk total publish
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_total_publish]" value="' . $total_publish . '">';
                $htmlReturn .= number_format($total_publish, 0, ',', '.');
                
                // Kolom untuk adult nett dengan input
                $htmlReturn .= '<td><center>';
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_adult_nett]" value="' . $avail->fba_adult_nett . '">';
                $htmlReturn .= number_format($avail->fba_adult_nett ?? 0, 0, ',', '.');
                $htmlReturn .= '</center></td>';

                // Kolom untuk child nett dengan input
                $htmlReturn .= '<td><center>';
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_child_nett]" value="' . $avail->fba_child_nett . '">';
                $htmlReturn .= number_format($avail->fba_child_nett ?? 0, 0, ',', '.');
                $htmlReturn .= '</center></td>';

                // Perhitungan Total Nett (Adult Nett * Adult Count + Child Nett * Child Count)
                $total_nett = (($avail->fba_adult_nett ?? 0) * $adultCountReturn) + (($avail->fba_child_nett ?? 0) * $childCountReturn);

                // Kolom untuk total nett
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_total_nett]" value="' . $total_nett . '">';
                $htmlReturn .= number_format($total_nett, 0, ',', '.');

                // Kolom untuk discount dengan input
                $htmlReturn .= '<td><center>';
                $htmlReturn .= '<input type="hidden" name="fbo_availability_id_return[' . $avail->fba_id . '][fbo_discount]" value="' . $avail->fba_discount . '">';
                $htmlReturn .= number_format($avail->fba_discount ?? 0, 0, ',', '.');
                $htmlReturn .= '</center></td>';

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
                'fbo_pickups_return' => $pickupAreas,
                'fbo_dropoffs_return' => $dropoffAreas,
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
                    'fbo_departure_times_return' => [],
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
                'fbo_departure_times_return' => $timeDepts,
                'show_shuttle_checkbox_return' => $showShuttleCheckbox,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
