<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\DataFastboat;
use App\Models\DataRoute;
use App\Models\FastboatAvailability;
use App\Models\MasterPort;
use App\Models\FastboatTrip;
use App\Models\MasterCurrency;
use App\Models\MasterNationality;
use App\Models\MasterPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingDataController extends Controller
{
    public function index()
    {
        return view('booking.data.index');
    }

    public function add()
    {
        $trip = FastboatTrip::all();
        $fastboat = DataFastboat::all();
        $departure = MasterPort::all();
        $arrival = MasterPort::all();
        $route = DataRoute::all();
        $deptTime = FastboatTrip::all();
        $availability = FastboatAvailability::all();
        $currency = MasterCurrency::where('cy_status', '1')->get();
        $nationality = MasterNationality::all();
        $payment_method = MasterPaymentMethod::all();
        $contact = Contact::all();
        return view('booking.data.add', compact('trip', 'fastboat', 'departure', 'arrival', 'route', 'deptTime', 'availability', 'currency', 'nationality', 'payment_method', 'contact'));
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
        // Mendapatkan IP address
        $ipAddress = $request->ip(); // IP Publik pengguna

        // Jika berada di belakang proxy, coba ambil IP dari X-Forwarded-For
        if ($request->server('HTTP_X_FORWARDED_FOR')) {
            $ipAddress = $request->server('HTTP_X_FORWARDED_FOR');
        } else {
            $ipAddress = $request->ip();
        }
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
        dd($contactData);

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
            $adultCount = $request->input('adult_count', 1); // Default 1 dewasa
            $childCount = $request->input('child_count', 0); // Default 0 anak-anak

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
                    ->whereDate('fbt_trip_date', $tripDate)
                    ->where('fbt_stock', '>', $totalCustomer); // Asumsikan ada field fbt_stock

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
            $discountPerPerson = 0;

            foreach ($availability as $avail) {
                $trip = $avail->trip;
                $deptTime = $avail->fba_dept_time ?? $trip->fbt_dept_time;
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

                $adultPublishTotal += $avail->fba_adult_publish ?? 0;
                $childPublishTotal += $avail->fba_child_publish ?? 0;

                $discountPerPerson = $avail->fba_discount ?? 0;
            }

            return response()->json([
                'html' => $html,
                'card_title' => $cardTitle,
                'adult_publish' => number_format($adultPublishTotal, 0, ',', '.'),
                'child_publish' => number_format($childPublishTotal, 0, ',', '.'),
                'discount' => number_format($discountPerPerson, 0, ',', '.'),
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
            $adultCount = $request->input('adult_count', 1); // Default 1 dewasa
            $childCount = $request->input('child_count', 0); // Default 0 anak-anak

            // Hitung total customer (dewasa + anak-anak)
            $totalCustomer = $adultCount + $childCount;

            // Filter berdasarkan tanggal
            $query = FastboatAvailability::where('fba_date', $tripDate)
                // Tambahkan filter stok harus lebih dari total customer
                ->where('fba_stock', '>', $totalCustomer);

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
                    'departure_ports' => [],
                    'arrival_ports' => [],
                    'fast_boats' => [],
                    'time_depts' => [],
                ]);
            }

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

    public function searchReturn(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDateReturn = $request->input('trip_return_date');
            $departurePortReturn = $request->input('departure_return_port');
            $arrivalPortReturn = $request->input('arrival_return_port');
            $fastBoatReturn = $request->input('fast_boat_return');
            $timeDeptReturn = $request->input('time_dept_return');
            $adultCountReturn = $request->input('adult_count', 1); // Default 1 dewasa
            $childCountReturn = $request->input('child_count', 0); // Default 0 anak-anak

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
                    ->whereDate('fbt_trip_date', $tripDateReturn)
                    ->where('fbt_stock', '>', $totalCustomerReturn);

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
            $htmlReturn = '';
            $cardTitleReturn = '';
            $adultPublishTotalReturn = 0;
            $childPublishTotalReturn = 0;
            $discountPerPersonReturn = 0;

            foreach ($availability as $avail) {
                $trip = $avail->trip;

                // Tentukan waktu keberangkatan
                $deptTimeReturn = $avail->fba_dept_time ?? $trip->fbt_dept_time;

                // Buat card-title
                $cardTitleReturn = '<center>' . $trip->fastboat->fb_name . ' (' .
                    $trip->departure->prt_code . ' -> ' .
                    $trip->arrival->prt_code . ' ' .
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
            }

            // Return hasil HTML dan perhitungan
            return response()->json([
                'htmlReturn' => $htmlReturn,
                'card_return_title' => $cardTitleReturn,
                'adult_return_publish' => number_format($adultPublishTotalReturn, 0, ',', '.'),
                'child_return_publish' => number_format($childPublishTotalReturn, 0, ',', '.'),
                'discount_return' => number_format($discountPerPersonReturn, 0, ',', '.'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function getFilteredDataReturn(Request $request)
    {
        try {
            // Ambil data dari request
            $tripDateReturn = $request->input('trip_return_date');
            $departurePortReturn = $request->input('departure_return_port');
            $arrivalPortReturn = $request->input('arrival_return_port');
            $fastBoatReturn = $request->input('fast_boat_return');
            $adultCountReturn = $request->input('adult_count', 1); // Default 1 dewasa
            $childCountReturn = $request->input('child_count', 0); // Default 0 anak-anak

            // Hitung total customer (dewasa + anak-anak)
            $totalCustomerReturn = $adultCountReturn + $childCountReturn;

            // Filter berdasarkan tanggal dan stok
            $query = FastboatAvailability::where('fba_date', $tripDateReturn)
                ->where('fba_stock', '>', $totalCustomerReturn);

            // Filter berdasarkan departure port
            if ($departurePortReturn) {
                $query->whereHas('trip.departure', function ($q) use ($departurePortReturn) {
                    $q->where('prt_name_en', $departurePortReturn);
                });
            }

            // Filter berdasarkan arrival port
            if ($arrivalPortReturn) {
                $query->whereHas('trip.arrival', function ($q) use ($arrivalPortReturn) {
                    $q->where('prt_name_en', $arrivalPortReturn);
                });
            }

            // Filter berdasarkan fastboat
            if ($fastBoatReturn) {
                $query->whereHas('trip.fastboat', function ($q) use ($fastBoatReturn) {
                    $q->where('fb_name', $fastBoatReturn);
                });
            }

            $availabilitiesReturn = $query->get();

            // Jika tidak ada availability, tidak perlu melanjutkan
            if ($availabilitiesReturn->isEmpty()) {
                return response()->json([
                    'departure_return_ports' => [],
                    'arrival_return_ports' => [],
                    'fast_boats_return' => [],
                    'time_depts_return' => [],
                ]);
            }

            // Format data untuk dropdown
            $departurePortsReturn = [];
            $arrivalPortsReturn = [];
            $fastBoatsReturn = [];
            $timeDeptsReturn = [];

            foreach ($availabilitiesReturn as $availability) {
                $trip = $availability->trip;

                // Mengumpulkan departure port
                if (!in_array($trip->departure->prt_name_en, $departurePortsReturn)) {
                    $departurePortsReturn[] = $trip->departure->prt_name_en;
                }

                // Mengumpulkan arrival port
                if (!in_array($trip->arrival->prt_name_en, $arrivalPortsReturn)) {
                    $arrivalPortsReturn[] = $trip->arrival->prt_name_en;
                }

                // Mengumpulkan fastboat
                if (!in_array($trip->fastboat->fb_name, $fastBoatsReturn)) {
                    $fastBoatsReturn[] = $trip->fastboat->fb_name;
                }

                // Mengumpulkan waktu keberangkatan
                $deptTimeReturn = $availability->fba_dept_time ?? $trip->fbt_dept_time;
                $formattedTimeDeptReturn = date('H:i', strtotime($deptTimeReturn));

                if (!in_array($formattedTimeDeptReturn, $timeDeptsReturn)) {
                    $timeDeptsReturn[] = $formattedTimeDeptReturn;
                }
            }

            // Return data untuk setiap dropdown
            return response()->json([
                'departure_return_ports' => $departurePortsReturn,
                'arrival_return_ports' => $arrivalPortsReturn,
                'fast_boats_return' => $fastBoatsReturn,
                'time_depts_return' => $timeDeptsReturn,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
