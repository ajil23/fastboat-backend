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
use App\Models\FastboatLog;
use Carbon\Carbon;
use COM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingDataController extends Controller
{
    public function index()
    {
        $bookingData = BookingData::orderBy('created_at', 'desc')->whereIn('fbo_transaction_status', ['accepted', 'confirmed'])->get();
        $paymentMethod = MasterPaymentMethod::all();
        return view('booking.data.index', compact('bookingData', 'paymentMethod'));
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
            // Mendapatkan IP address
            $ipAddress = $request->ip(); // IP Publik pengguna

            // Jika berada di belakang proxy, coba ambil IP dari X-Forwarded-For
            if ($request->server('HTTP_X_FORWARDED_FOR')) {
                $ipAddress = $request->server('HTTP_X_FORWARDED_FOR');
            } else {
                $ipAddress = $request->ip();
            }

            // Total penumpang
            $totalPassenger = $request->fbo_adult + $request->fbo_child;

            // Simpan data kontak utama
            $contactData = new Contact();
            $contactData->ctc_order_id = $this->generateOrderId();
            $contactData->ctc_name = $request->ctc_name;
            $contactData->ctc_email = $request->ctc_email;
            $contactData->ctc_phone = $request->ctc_phone;
            $contactData->ctc_nationality = $request->ctc_nationality;
            $contactData->ctc_note = '';
            $contactData->ctc_order_type = 'F';
            $contactData->ctc_booking_date = Carbon::now()->toDateString();  // Mengambil tanggal saat ini
            $contactData->ctc_booking_time = Carbon::now()->toTimeString();
            $contactData->ctc_ip_address = $ipAddress;
            $contactData->ctc_browser = $request->header('User-Agent');
            $contactData->ctc_updated_by = Auth()->id();
            $contactData->ctc_created_by = Auth()->id();
            $contactData->save();

            // Ambil ctc_id kontak yang baru dibuat
            $contactId = $contactData->ctc_id;

            // Jika masih null, coba query ulang
            if ($contactId === null) {
                $latestContact = Contact::latest('ctc_id')->first();
                $contactId = $latestContact ? $latestContact->ctc_id : null;
            }

            if ($contactId === null) {
                throw new \Exception('Failed to retrieve Contact ctc_id');
            }

            $departureSuffix = $request->has('switch') ? 'Y' : 'X'; // Menentukan apakah data depart berjenis one way atau round trip
            $keys = array_keys($request->input('fbo_availability_id'));
            $availabilityId = $keys[0];

            // Membuat data Booking
            $bookingDataDepart = new BookingData();
            $bookingDataDepart->fbo_order_id = $contactId;
            $bookingDataDepart->fbo_booking_id = 'F' . $contactData->ctc_order_id . $departureSuffix;
            $bookingDataDepart->fbo_availability_id = $availabilityId;

            // Mendapatkan id trip dari fast-boat availability
            $trip = FastboatAvailability::find($availabilityId);

            if ($trip) {
                // Mengambil fba_trip_id
                $bookingDataDepart->fbo_trip_id = $trip->fba_trip_id;
            } else {
                // Menangani jika tidak ditemukan
                throw new \Exception("FastboatAvailability with ID {$availabilityId} not found.");
            }

            $fbo_payment_status = $request->fbo_payment_status;
            $fbo_payment_method = $request->fbo_payment_method;
            $fbo_transaction_id = $request->fbo_transaction_id;
            if ($fbo_payment_status == 'unpaid') {
                $bookingDataDepart->fbo_payment_method = "";
                $bookingDataDepart->fbo_transaction_id = "";
                $bookingDataDepart->fbo_payment_status = "";
                $bookingDataDepart->fbo_transaction_status = "waiting";
            } else {
                if ($fbo_payment_method == 'paypal') {
                    $bookingDataDepart->fbo_payment_method = "paypal";
                    $bookingDataDepart->fbo_transaction_id = $fbo_transaction_id;
                    $bookingDataDepart->fbo_payment_status = $fbo_payment_status;
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                } elseif ($fbo_payment_method == 'midtrans') {
                    $bookingDataDepart->fbo_payment_method = "midtrans";
                    $bookingDataDepart->fbo_transaction_id = $fbo_transaction_id;
                    $bookingDataDepart->fbo_payment_status = $fbo_payment_status;
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                } elseif ($fbo_payment_method == 'bank_transfer') {
                    $bookingDataDepart->fbo_payment_method = "bank transfer";
                    $bookingDataDepart->fbo_transaction_id = $fbo_transaction_id;
                    $bookingDataDepart->fbo_payment_status = $fbo_payment_status;
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                } elseif ($fbo_payment_method == 'pak_anang') {
                    $bookingDataDepart->fbo_payment_method = "pak anang";
                    $bookingDataDepart->fbo_transaction_id = "recived by mr. anang";
                    $bookingDataDepart->fbo_payment_status = $fbo_payment_status;
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                } elseif ($fbo_payment_method == 'pay_on_port') {
                    $bookingDataDepart->fbo_payment_method = "collect";
                    $bookingDataDepart->fbo_transaction_id = "collect";
                    $bookingDataDepart->fbo_payment_status = "unpaid";
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                } elseif ($fbo_payment_method == 'cash') {
                    $bookingDataDepart->fbo_payment_method = "cash";
                    $bookingDataDepart->fbo_transaction_id = "recived by " . $fbo_transaction_id;
                    $bookingDataDepart->fbo_payment_status = $fbo_payment_status;
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                } else {
                    $bookingDataDepart->fbo_payment_method = "gilitransfers agen";
                    $bookingDataDepart->fbo_transaction_id = $fbo_transaction_id;
                    $bookingDataDepart->fbo_payment_status = $fbo_payment_status;
                    $bookingDataDepart->fbo_transaction_status = "accepted";
                }
            }

            $bookingDataDepart->fbo_currency = $request->fbo_currency;
            $bookingDataDepart->fbo_trip_date = $request->fbo_trip_date;
            $bookingDataDepart->fbo_adult_nett = $request->input("fbo_availability_id.$availabilityId.fbo_adult_nett");
            $bookingDataDepart->fbo_child_nett = $request->input("fbo_availability_id.$availabilityId.fbo_child_nett");
            $bookingDataDepart->fbo_total_nett = $request->input("fbo_availability_id.$availabilityId.fbo_total_nett");
            $bookingDataDepart->fbo_adult_publish = $request->input("fbo_availability_id.$availabilityId.fbo_adult_publish");
            $bookingDataDepart->fbo_child_publish = $request->input("fbo_availability_id.$availabilityId.fbo_child_publish");
            $bookingDataDepart->fbo_total_publish = $request->input("fbo_availability_id.$availabilityId.fbo_total_publish");

            // Mencari nilai kurs yang sesuai dengan fbo_currency
            $currency = MasterCurrency::where('cy_code', $bookingDataDepart->fbo_currency)->first();

            if (!$currency) {
                throw new \Exception("Rate {$bookingDataDepart->fbo_currency} not found.");
            }
            $bookingDataDepart->fbo_kurs = $currency->cy_rate;

            $bookingDataDepart->fbo_passenger = $request->fbo_passenger;
            $bookingDataDepart->fbo_adult = $request->fbo_adult;
            $bookingDataDepart->fbo_child = $request->fbo_child;
            $bookingDataDepart->fbo_infant = $request->fbo_infant;
            if ($bookingDataDepart->fbo_kurs == 0) {
                toast('Rate cannot be zero', 'error');
            } else {
                $bookingDataDepart->fbo_adult_currency = round($request->price_adult / $bookingDataDepart->fbo_kurs);
                $bookingDataDepart->fbo_child_currency = round($request->price_child / $bookingDataDepart->fbo_kurs);
                $bookingDataDepart->fbo_total_currency = round(($bookingDataDepart->fbo_adult_currency * $bookingDataDepart->fbo_adult) + ($bookingDataDepart->fbo_child_currency * $bookingDataDepart->fbo_child));
            }
            $discount = $request->input("fbo_availability_id.$availabilityId.fbo_dicount");
            $bookingDataDepart->fbo_discount = $discount * ($bookingDataDepart->fbo_adult + $bookingDataDepart->fbo_child);

            // Menentukan price cut
            if ($bookingDataDepart->fbo_adult_currency > $bookingDataDepart->fbo_adult_publish) {
                // Apabila adult currency lebih besar dari adult publish maka price cut nya hanya dari child
                $bookingDataDepart->fbo_price_cut = ((($bookingDataDepart->fbo_child_publish - $request->price_child) * $bookingDataDepart->fbo_child));
            } elseif ($bookingDataDepart->fbo_child_currency > $bookingDataDepart->fbo_child_publish) {
                // Apabila child currency lebih besar adri child publish maka price cut nya hanya dari adult
                $bookingDataDepart->fbo_price_cut = ((($bookingDataDepart->fbo_adult_publish - $request->price_adult) * $bookingDataDepart->fbo_adult));
            } else {
                // Apabila semua kondisi tidak terpenuhi maka lakukan kalkulasi pada adult & child
                $bookingDataDepart->fbo_price_cut = ((($bookingDataDepart->fbo_adult_publish - $request->price_adult) * $bookingDataDepart->fbo_adult) + (($bookingDataDepart->fbo_child_publish - $request->price_child) * $bookingDataDepart->fbo_child));
            }

            $bookingDataDepart->fbo_discount_total = $bookingDataDepart->fbo_discount + $bookingDataDepart->fbo_price_cut;
            $bookingDataDepart->fbo_refund = "";
            $bookingDataDepart->fbo_end_total = $request->fbo_end_total;
            $bookingDataDepart->fbo_end_total_currency = $request->fbo_end_total_currency;
            $bookingDataDepart->fbo_profit = $bookingDataDepart->fbo_end_total - $bookingDataDepart->fbo_total_nett;
            $bookingDataDepart->fbo_fast_boat = $trip->trip->fastboat->fb_id;
            $bookingDataDepart->fbo_company = $trip->trip->fastboat->company->cpn_id;
            $bookingDataDepart->fbo_departure_island = $trip->trip->departure->island->isd_id;
            $bookingDataDepart->fbo_departure_port = $trip->trip->departure->prt_id;
            $bookingDataDepart->fbo_departure_time = $request->fbo_departure_time;
            $bookingDataDepart->fbo_arrival_island = $trip->trip->arrival->island->isd_id;
            $bookingDataDepart->fbo_arrival_port = $trip->trip->arrival->prt_id;
            $bookingDataDepart->fbo_arrival_time = $trip->fba_arrival_time ?? $trip->trip->fbt_arrival_time;
            $bookingDataDepart->fbo_checkin_point = 1; // Harus terkoneksi ke tabel checkin point
            $bookingDataDepart->fbo_mail_admin = "";
            $bookingDataDepart->fbo_mail_client = "";
            $bookingDataDepart->fbo_pickup = $request->fbo_pickup;
            $bookingDataDepart->fbo_dropoff = $request->fbo_dropoff;
            $bookingDataDepart->fbo_specific_pickup = $request->fbo_specific_pickup;
            $bookingDataDepart->fbo_specific_dropoff = $request->fbo_specific_dropoff;
            $bookingDataDepart->fbo_contact_pickup = $request->fbo_contact_pickup;
            $bookingDataDepart->fbo_contact_dropoff = $request->fbo_contact_dropoff;
            $bookingDataDepart->fbo_log;
            $bookingDataDepart->fbo_source = "backoffice";
            $bookingDataDepart->fbo_updated_by = Auth()->id();
            $bookingDataDepart->save();

            // Pengecekan ketersediaan stok sekaligus penanganan race condition
            $stokDataDepart = FastboatAvailability::where('fba_id', $availabilityId)->lockForUpdate()->first();
            if ($stokDataDepart->fba_stock < $totalPassenger  + 1) {
                return response()->json(['message' => 'Ticket stock is low'], 400);
            }

            // Pengurangan stok di availability
            $stokDataDepart->fba_stock -= $totalPassenger;
            $stokDataDepart->save();


            // Membuat data Booking untuk return
            if ($request->has('switch')) {
                $keys = array_keys($request->input('fbo_availability_id_return')); // Memecah array untuk mengambil nilai id dari availability
                $availabilityReturnId = $keys[0];
                $bookingDataReturn = new BookingData();
                $bookingDataReturn->fbo_order_id = $contactId;
                $bookingDataReturn->fbo_booking_id = 'F' . $contactData->ctc_order_id . 'Z';
                $bookingDataReturn->fbo_availability_id = $availabilityReturnId;

                // Mendapatkan id trip dari fast-boat availability
                $trip = FastboatAvailability::find($availabilityReturnId);

                if ($trip) {
                    // Mengambil fba_trip_id
                    $bookingDataReturn->fbo_trip_id = $trip->fba_trip_id;
                } else {
                    // Menangani jika tidak ditemukan
                    throw new \Exception("FastboatAvailability with ID {$availabilityReturnId} not found.");
                }


                $bookingDataReturn->fbo_payment_status = $bookingDataDepart->fbo_payment_status;
                $bookingDataReturn->fbo_payment_method = $bookingDataDepart->fbo_payment_method;
                $bookingDataReturn->fbo_transaction_id = $bookingDataDepart->fbo_transaction_id;
                $bookingDataReturn->fbo_transaction_status = $bookingDataDepart->fbo_transaction_status;
                $bookingDataReturn->fbo_currency = $request->fbo_currency;
                $bookingDataReturn->fbo_trip_date = $request->trip_return_date;
                $bookingDataReturn->fbo_kurs = $bookingDataDepart->fbo_kurs;
                $bookingDataReturn->fbo_adult_nett = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_adult_nett");
                $bookingDataReturn->fbo_child_nett = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_child_nett");
                $bookingDataReturn->fbo_total_nett = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_total_nett");
                $bookingDataReturn->fbo_adult_publish = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_adult_publish");
                $bookingDataReturn->fbo_child_publish = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_child_publish");
                $bookingDataReturn->fbo_total_publish = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_total_publish");
                $bookingDataReturn->fbo_adult = $request->fbo_adult;
                $bookingDataReturn->fbo_child = $request->fbo_child;
                $bookingDataReturn->fbo_infant = $request->fbo_infant;

                if ($bookingDataDepart->fbo_kurs == 0) {
                    toast('Rate cannot be zero', 'error');
                } else {
                    $bookingDataReturn->fbo_adult_currency = round($request->adult_return_publish / $bookingDataDepart->fbo_kurs);
                    $bookingDataReturn->fbo_child_currency = round($request->child_return_publish / $bookingDataDepart->fbo_kurs);
                    $bookingDataReturn->fbo_total_currency = round(($bookingDataReturn->fbo_adult_currency * $bookingDataReturn->fbo_adult) + ($bookingDataReturn->fbo_child_currency * $bookingDataReturn->fbo_child));
                }
                $discountReturn = $request->input("fbo_availability_id_return.$availabilityReturnId.fbo_discount");
                $bookingDataReturn->fbo_discount = $discountReturn * ($bookingDataReturn->fbo_adult + $bookingDataReturn->fbo_child);

                // Menentukan price cut
                if ($bookingDataReturn->fbo_adult_currency > $bookingDataReturn->fbo_adult_publish) {
                    // Apabila adult currency lebih besar dari adult publish maka price cut nya hanya dari child
                    $bookingDataReturn->fbo_price_cut = ((($bookingDataReturn->fbo_child_publish - $request->child_return_publish) * $bookingDataReturn->fbo_child));
                } elseif ($bookingDataReturn->fbo_child_currency > $bookingDataReturn->fbo_child_publish) {
                    // Apabila child currency lebih besar adri child publish maka price cut nya hanya dari adult
                    $bookingDataReturn->fbo_price_cut = ((($bookingDataReturn->fbo_adult_publish - $request->adult_return_publish) * $bookingDataReturn->fbo_adult));
                } else {
                    // Apabila semua kondisi tidak terpenuhi maka lakukan kalkulasi pada adult & child
                    $bookingDataReturn->fbo_price_cut = ((($bookingDataReturn->fbo_adult_publish - $request->adult_return_publish) * $bookingDataReturn->fbo_adult) + (($bookingDataReturn->fbo_child_publish - $request->child_return_publish) * $bookingDataReturn->fbo_child));
                }

                $bookingDataReturn->fbo_discount_total = $bookingDataReturn->fbo_discount + $bookingDataReturn->fbo_price_cut;
                $bookingDataReturn->fbo_refund = "";
                $bookingDataReturn->fbo_end_total = $request->fbo_end_total;
                $bookingDataReturn->fbo_end_total_currency = $request->currency_return_end;
                $bookingDataReturn->fbo_profit = $bookingDataReturn->fbo_end_total - $bookingDataReturn->fbo_total_nett;
                $bookingDataReturn->fbo_passenger = $request->fbo_passenger;
                $bookingDataReturn->fbo_fast_boat = $trip->trip->fastboat->fb_id;
                $bookingDataReturn->fbo_company = $trip->trip->fastboat->company->cpn_id;
                $bookingDataReturn->fbo_departure_island = $trip->trip->departure->island->isd_id;
                $bookingDataReturn->fbo_departure_port = $trip->trip->departure->prt_id;
                $bookingDataReturn->fbo_departure_time = $request->fbo_departure_time_return;
                $bookingDataReturn->fbo_arrival_island = $trip->trip->arrival->island->isd_id;
                $bookingDataReturn->fbo_arrival_port = $trip->trip->arrival->prt_id;
                $bookingDataReturn->fbo_arrival_time = $trip->fba_arrival_time ?? $trip->trip->fbt_arrival_time;
                $bookingDataReturn->fbo_checkin_point = 1;
                $bookingDataReturn->fbo_mail_admin = "";
                $bookingDataReturn->fbo_mail_client = "";
                $bookingDataReturn->fbo_pickup = $request->fbo_pickup_return;
                $bookingDataReturn->fbo_dropoff = $request->fbo_dropoff_return;
                $bookingDataReturn->fbo_specific_pickup = $request->fbo_specific_pickup_return;
                $bookingDataReturn->fbo_specific_dropoff = $request->fbo_specific_dropoff_return;
                $bookingDataReturn->fbo_contact_pickup = $request->fbo_contact_pickup_return;
                $bookingDataReturn->fbo_contact_dropoff = $request->fbo_pickup;
                $bookingDataReturn->fbo_log;
                $bookingDataReturn->fbo_source = "backoffice";
                $bookingDataReturn->fbo_updated_by = Auth()->id();
                $bookingDataReturn->save();

                // Pengecekan ketersediaan stok sekaligus penanganan race condition
                $stokDataReturn = FastboatAvailability::where('fba_id', $availabilityReturnId)->lockForUpdate()->first();
                if ($stokDataReturn->fba_stock < $totalPassenger  + 1) {
                    return response()->json(['message' => 'Ticket stock is low'], 400);
                }

                // Pengurangan stok di availability
                $stokDataReturn->fba_stock -= $totalPassenger;
                $stokDataReturn->save();
            }

            // Commit transaksi jika semua proses berhasil
            DB::commit();
            toast('Data booking as been added!', 'success');
            return redirect()->route('data.view');
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

    // Menangani perubahan status 
    public function status($fbo_id)
    {
        DB::beginTransaction();
        try {
            $bookingData = BookingData::find($fbo_id); // Ambil data berdasarkan ID

            // Pengecekan data fbo_log
            $before = $bookingData->fbo_log;
            if ($before != NULL) {
                $logbefore = $before . ';';
            } else {
                $logbefore = '';
            }
            $user = Auth::user()->name; // Pengecekan user
            $date = now()->format('d-M-Y H:i:s'); // Tanggal 


            if ($bookingData && $bookingData->fbo_payment_status == 'paid') { // Cek payment status
                $oldStatus = $bookingData->fbo_transaction_status; // Status sebelum diubah

                // Proses pengubahan status transaksi
                if ($bookingData->fbo_transaction_status == 'waiting') {
                    $bookingData->fbo_transaction_status = 'accepted';
                } elseif ($bookingData->fbo_transaction_status == 'accepted') {
                    $bookingData->fbo_transaction_status = 'confirmed';
                    // Buat log perubahan status

                    $count = FastboatLog::where('fbl_booking_id', $bookingData->fbo_booking_id)
                        ->where('fbl_type', 'like', 'Update transaction status%')
                        ->count();

                    FastboatLog::create([
                        'fbl_booking_id' => $bookingData->fbo_booking_id,
                        'fbl_type' => 'Update transaction status ' . ($count + 1),
                        'fbl_data_before' => 'transaction_status:accepted',
                        'fbl_data_after' => 'transaction_status:confirmed',
                    ]);

                    // Simpan log ke kolom `fbo_log` pada tabel booking_data
                    $bookingData->fbo_log = $logbefore . $user . ',' . 'Mark as confirm' . ',' . $date;
                    $bookingData->save();
                } elseif ($bookingData->fbo_transaction_status == 'confirmed') {
                    $bookingData->fbo_transaction_status = 'accepted'; // Jika confirmed, kembalikan ke accepted

                    $count = FastboatLog::where('fbl_booking_id', $bookingData->fbo_booking_id)
                        ->where('fbl_type', 'like', 'Update transaction status%')
                        ->count();

                    // Buat log perubahan status
                    FastboatLog::create([
                        'fbl_booking_id' => $bookingData->fbo_booking_id,
                        'fbl_type' => 'Update transaction status ' . ($count + 1),
                        'fbl_data_before' => 'transaction_status:confirmed',
                        'fbl_data_after' => 'transaction_status:accepted',
                    ]);

                    // Simpan log ke kolom `fbo_log` pada tabel booking_data
                    $bookingData->fbo_log = $logbefore . $user . ',' . 'Mark as unconfirm' . ',' . $date;
                    $bookingData->save();
                }

                $newStatus = $bookingData->fbo_transaction_status; // Status setelah diubah

                // Simpan perubahan ke database
                toast('Status Transaction as been updated!', 'success');
                $bookingData->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengubah status: ' . $e->getMessage()]);
        }
        return back();
    }

    public function updatePayment(Request $request)
    {
        // Validasi request
        $request->validate([
            'fbo_id' => 'required',
            'fbo_payment_method' => 'required|string',
        ]);

        // Cari data booking berdasarkan fbo_id
        $bookingData = BookingData::find($request->fbo_id);

        if ($bookingData) {
            // Set status pembayaran ke 'paid'
            $bookingData->fbo_payment_status = 'paid';

            // Set metode pembayaran
            $bookingData->fbo_payment_method = $request->fbo_payment_method;

            // Logika untuk menentukan fbo_transaction_id
            $fbo_transaction_id = '';

            if ($request->fbo_payment_method == 'pak_anang') {
                $fbo_transaction_id = 'received by Mr. Anang';
            } elseif ($request->fbo_payment_method == 'pay_on_port') {
                $fbo_transaction_id = 'collect';
            } elseif ($request->fbo_payment_method == 'cash') {
                $fbo_transaction_id = 'received by ' . $request->input('fbo_transaction_id');
            } else {
                // Gunakan nilai dari input pengguna untuk metode lain
                $fbo_transaction_id = $request->input('fbo_transaction_id');
            }

            // Set fbo_transaction_id dan status transaksi
            $bookingData->fbo_transaction_id = $fbo_transaction_id;
            $bookingData->fbo_transaction_status = 'accepted';

            // Simpan perubahan
            $bookingData->save();
        }

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Payment status updated to paid.');
    }

    // Menampilkan modal detail data fast-boat
    public function show($fbo_id)
    {
        $bookingData = BookingData::with(['trip.fastboat', 'trip.departure.island', 'trip.arrival.island', 'contact', 'checkPoint'])
            ->find($fbo_id);

        if (!$bookingData) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $passengerDataString = $bookingData->fbo_passenger; // Mengambil data dari database
        $passengerArray = [];


        // Memisahkan data berdasarkan ';' untuk setiap penumpang
        $passengers = explode(';', $passengerDataString);

        // Mengurai setiap data penumpang
        foreach ($passengers as $passenger) {
            $details = explode(',', $passenger);
            if (count($details) === 4) {
                $passengerArray[] = [
                    'name' => $details[0],
                    'age' => $details[1],
                    'gender' => $details[2],
                    'nationality' => $details[3],
                ];
            }
        }

        // Mengambil dan memformat data log
        $logDataString = $bookingData->fbo_log;
        $logArray = [];

        $logs = explode(';', $logDataString);

        foreach ($logs as $log) {
            $logDetails = explode(',', $log);
            if (count($logDetails) === 3) {
                $logArray[] = array(
                    'user' => trim($logDetails[0]),
                    'activity' => trim($logDetails[1]),
                    'date' => trim($logDetails[2])
                );
            }
        }

        return response()->json([
            'fbo_booking_id' => $bookingData->fbo_booking_id,
            'fbo_trip_date' => $bookingData->fbo_trip_date,
            'fbo_child' => $bookingData->fbo_child,
            'fbo_infant' => $bookingData->fbo_infant,
            'fbo_adult_publish' => $bookingData->fbo_adult_publish,
            'fbo_child_publish' => $bookingData->fbo_child_publish,
            'fbo_infant_publish' => $bookingData->fbo_infant_publish,
            'fbo_total_publish' => $bookingData->fbo_total_publish,
            'fbo_discount' => $bookingData->fbo_discount,
            'fbo_price_cut' => $bookingData->fbo_price_cut,
            'fbo_total_discount' => $bookingData->fbo_total_discount,
            'fbo_total_nett' => $bookingData->fbo_total_nett,
            'fbo_end_total' => $bookingData->fbo_end_total,
            'fbo_profit' => $bookingData->fbo_profit,
            'fbo_refund' => $bookingData->fbo_refund,
            'fbo_transaction_id' => $bookingData->fbo_transaction_id,
            'trip' => [
                'fastboat' => ['fb_name' => $bookingData->trip->fastboat->fb_name],
                'departure_port' => $bookingData->trip->departure->prt_name_en,
                'departure_island' => $bookingData->trip->departure->island->isd_name,
                'departure_time' => Carbon::parse($bookingData->fbo_departure_time)->format('H:i'),
                'arrival_port' => $bookingData->trip->arrival->prt_name_en,
                'arrival_island' => $bookingData->trip->arrival->island->isd_name,
                'arrival_time' => Carbon::parse($bookingData->fbo_arrival_time)->format('H:i'),
                'passengers' => $passengerArray,
            ],
            'checkPoint' => [
                'fcp_address' => $bookingData->checkPoint->fcp_address,
            ],
            'logs' => $logArray,
        ]);
    }

    public function updateStatus(Request $request, $fbo_id)
    {
        $bookingData = BookingData::findOrFail($fbo_id);  // Sesuaikan model dengan kebutuhan
        $bookingData->fbo_transaction_status = 'remove';
        $bookingData->save();

        toast('Status Transaction as been removed!', 'success');
        return response()->json(['success' => true]);
    }

    public function whatsappReservation($fbo_id)
    {
        $booking = BookingData::with(['trip.fastboat', 'trip.fastboat.company', 'trip.departure', 'trip.arrival', 'contact', 'availability'])->findOrFail($fbo_id);

        $passengers = explode(';', $booking->fbo_passenger);
        $passengerArray = []; // Inisialisasi array penumpang

        // Mengurai setiap data penumpang
        foreach ($passengers as $passenger) {
            $details = explode(',', $passenger);
            if (count($details) === 4) {
                // Mengubah umur menjadi string sesuai kategori
                $age = (int) $details[1];
                if ($age > 13) {
                    $ageGroup = 'ADULT';
                } elseif ($age >= 3 && $age <= 12) {
                    $ageGroup = 'CHILD';
                } elseif ($age >= 0 && $age <= 2) {
                    $ageGroup = 'INFANT';
                } else {
                    $ageGroup = 'UNKNOWN'; // Jika umur tidak valid
                }

                $passengerArray[] = [
                    'name' => $details[0],
                    'age' => $ageGroup, // Menggunakan kategori umur
                    'gender' => $details[2],
                    'nationality' => $details[3],
                ];
            }
        }

        // Format trip date
        $tripDate = new \DateTime($booking->fbo_trip_date);
        $formattedDate = $tripDate->format('l, d M Y');

        // Memformat waktu
        $time = $booking->availability->fba_dept_time ?? $booking->trip->fbt_dept_time;
        $timeDateTime = new \DateTime($time);
        $formattedTime = $timeDateTime->format('H:i');

        return response()->json([
            'fbo_booking_id' => $booking->fbo_booking_id,
            'company' => $booking->trip->fastboat->company->cpn_name,
            'departure_port' => $booking->trip->departure->prt_name_en,
            'arrival_port' => $booking->trip->arrival->prt_name_en,
            'fbo_trip_date' => $formattedDate,
            'time' => $formattedTime,
            'name' => $booking->contact->ctc_name,
            'email' => $booking->contact->ctc_email,
            'phone' => $booking->contact->ctc_phone,
            'passengers' => $passengerArray,
            'cpn_phone' => $booking->trip->fastboat->company->cpn_phone,
        ]);
    }

    public function cancelTransaction(Request $request)
    {
        // Find the booking
        $bookingData = BookingData::find($request->fbo_id);

        if ($bookingData) {
            // Handle the refund options
            if ($request->fbo_payment_method == 'full_refund') {
                // Full refund: fbo_refund = fbo_end_total, fbo_profit = 0
                $bookingData->fbo_refund = $bookingData->fbo_end_total;
                $bookingData->fbo_profit = 0;
            } elseif ($request->fbo_payment_method == 'partial_refund') {
                // Partial refund: set refund, calculate profit
                $partialRefund = $request->partial_refund_amount;
                $bookingData->fbo_refund = $partialRefund;
                $bookingData->fbo_profit = $bookingData->fbo_end_total - $partialRefund;
            } elseif ($request->fbo_payment_method == 'full_charge') {
                // Full charge: refund = 0, profit = end total
                $bookingData->fbo_refund = 0;
                $bookingData->fbo_profit = $bookingData->fbo_end_total;
            }

            // Update other fields (if necessary)
            $bookingData->fbo_transaction_status = 'cancel';  // Example status
            $bookingData->fbo_payment_status = 'Refund';  // Example status
            $bookingData->fbo_payment_method = $request->fbo_payment_method ?? $bookingData->fbo_payment_method;

            // Save changes to the database
            $bookingData->save();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Transaction canceled successfully.');
        }

        // Return with error if booking not found
        return redirect()->back()->with('error', 'Transaction not found.');
    }

    public function viewTicket(Request $request, $ticketId)
    {
        $ticketType = $request->input('downloadTicket');
        $dataTicket = BookingData::with(['trip.fastboat', 'trip.fastboat.company', 'trip.departure', 'trip.arrival', 'contact', 'availability'])->findOrFail($ticketId);

        $passengers = explode(';', $dataTicket->fbo_passenger);
        $passengerArray = []; // Inisialisasi array penumpang

        // Mengurai setiap data penumpang
        foreach ($passengers as $passenger) {
            $details = explode(',', $passenger);
            if (count($details) === 4) {
                // Mengubah umur menjadi string sesuai kategori
                $age = (int) $details[1];
                if ($age > 13) {
                    $ageGroup = 'ADULT';
                } elseif ($age >= 3 && $age <= 12) {
                    $ageGroup = 'CHILD';
                } elseif ($age >= 0 && $age <= 2) {
                    $ageGroup = 'INFANT';
                } else {
                    $ageGroup = 'UNKNOWN'; // Jika umur tidak valid
                }

                $passengerArray[] = [
                    'name' => $details[0],
                    'age' => $ageGroup, // Menggunakan kategori umur
                    'gender' => $details[2],
                    'nationality' => $details[3],
                ];
            }
        }

        // Format trip date
        $tripDate = new \DateTime($dataTicket->fbo_trip_date);
        $formattedTripDate = $tripDate->format('l, d M Y');

        // Memformat waktu departur
        $time = $dataTicket->availability->fba_dept_time ?? $dataTicket->trip->fbt_dept_time;
        $timeDateTime = new \DateTime($time);
        $formattedTime = $timeDateTime->format('H:i');

        // Memformat waktu arrival
        $arrivaltime = $dataTicket->fbo_arrival_time;
        $arrivaltimeDateTime = new \DateTime($arrivaltime);
        $arrivalformattedTime = $arrivaltimeDateTime->format('H:i');

        // Memformat created_at
        $bookingDate = new \DateTime($dataTicket->created_at);
        $formattedBookingDate = $bookingDate->format('l, d M Y');

        function getImagePath($imagePath)
        {
            // Jika menggunakan asset()
            if (strpos($imagePath, 'assets/') === 0) {
                return public_path($imagePath);
            }

            // Handle path untuk cpn_logo
            // Karena di database mungkin hanya tersimpan nama filenya saja
            return storage_path('app/public/' . $imagePath);
        }


        $data = [
            'name' => $dataTicket->contact->ctc_name,
            'email' => $dataTicket->contact->ctc_email,
            'phone' => $dataTicket->contact->ctc_phone,
            'fbo_booking_id' => $dataTicket->fbo_booking_id,
            'fbo_payment_status' => $dataTicket->fbo_payment_status,
            'fbo_trip_date' => $formattedTripDate,
            'fbo_checkin_point_address' => $dataTicket->checkinPoint->fcp_address,
            'fbo_checkin_point_maps' => $dataTicket->checkinPoint->fcp_maps,
            'fbo_pickup' => $dataTicket->fbo_pickup,
            'fbo_specific_pickup' => $dataTicket->fbo_specific_pickup,
            'fbo_contact_pickup' => $dataTicket->fbo_contact_pickup,
            'fbo_dropoff' => $dataTicket->fbo_dropoff,
            'fbo_specific_dropoff' => $dataTicket->fbo_specific_dropoff,
            'fbo_contact_dropoff' => $dataTicket->fbo_contact_dropoff,
            'cpn_name' => $dataTicket->trip->fastboat->company->cpn_name,
            'cpn_email' => $dataTicket->trip->fastboat->company->cpn_email,
            'cpn_phone' => $dataTicket->trip->fastboat->company->cpn_phone,
            'cpn_logo' => base64_encode(file_get_contents(getImagePath($dataTicket->trip->fastboat->company->cpn_logo))),
            'departure_port' => $dataTicket->trip->departure->prt_name_en,
            'departure_island' => $dataTicket->trip->departure->island->isd_name,
            'departure_time' => $formattedTime,
            'arrival_port' => $dataTicket->trip->arrival->prt_name_en,
            'arrival_island' => $dataTicket->trip->arrival->island->isd_name,
            'arrival_time' => $arrivalformattedTime,
            'passengers' => $passengerArray,
            'logo_ticket' => base64_encode(file_get_contents(public_path('assets/images/logo-ticket.png'))),
            'created_at' => $formattedBookingDate,
        ];

        if ($ticketType == "gt") {
            $pdf = Pdf::loadView('ticket.gt', $data);
            return $pdf->stream('ticket.pdf');
        } elseif ($ticketType == "agen1") {
            return view('ticket.agen1');
        } else {
            return view('ticket.agen2');
        }
    }
}
