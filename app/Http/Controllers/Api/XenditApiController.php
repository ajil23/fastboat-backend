<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\CustomerMail;
use App\Mail\SupplierMail;
use Illuminate\Support\Facades\Mail;
use App\Helpers;

class XenditApiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai contactId dari query parameter
        $orderId = $request->input('orderId');

        $contact = Contact::where('ctc_order_id', $orderId)->first();
        $contactId = $contact->ctc_id;

        // Query untuk One Way (booking dengan fbo_booking_id yang mengandung 'X')
        $bookingOneWay = BookingData::where('fbo_order_id', $contactId)
            ->where('fbo_booking_id', 'like', '%X')
            ->first();

        // Query untuk Depart (booking dengan fbo_booking_id yang mengandung 'Y')
        $bookingDepart = BookingData::where('fbo_order_id', $contactId)
            ->where('fbo_booking_id', 'like', '%Y')
            ->first();

        // Query untuk Return (booking dengan fbo_booking_id yang mengandung 'Z')
        $bookingReturn = BookingData::where('fbo_order_id', $contactId)
            ->where('fbo_booking_id', 'like', '%Z')
            ->first();


        // Inisialisasi data untuk One Way jika booking ditemukan
        $oneway = null;
        if ($bookingOneWay) {
            $oneway = [
                'passenger' => $bookingOneWay->fbo_passenger,
                'adult' => $bookingOneWay->fbo_adult,
                'child' => $bookingOneWay->fbo_child,
                'infant' => $bookingOneWay->fbo_infant,
                'fba_id' => $bookingOneWay->availability->fba_id,
                'fba_dept_time' => $bookingOneWay->availability->fba_dept_time ?? $bookingOneWay->availability->trip->fbt_dept_time,
                'fba_arrival_time' => $bookingOneWay->availability->fba_arrival_time ?? $bookingOneWay->availability->trip->fbt_arrival_time,
                'fb_image1' => url('storage/' . ltrim($bookingOneWay->availability->trip->fastboat->fb_image1, '/')) ?? null,
                'cpn_name' => $bookingOneWay->availability->trip->fastboat->company->cpn_name ?? null,
                'cpn_logo' => url('storage/' . ltrim($bookingOneWay->availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                'fbo_adult_publish' => $bookingOneWay->fbo_adult_publish,
                'fbo_child_publish' => $bookingOneWay->fbo_child_publish
            ];
        }

        // Inisialisasi data untuk Depart dan Return jika booking ditemukan
        $depart = null;
        $return = null;

        if ($bookingDepart && $bookingReturn) {
            // Jika ada booking Depart dan Return, maka ini adalah Round Trip
            $depart = [
                'passenger' => $bookingDepart->fbo_passenger,
                'adult' => $bookingDepart->fbo_adult,
                'child' => $bookingDepart->fbo_child,
                'infant' => $bookingDepart->fbo_infant,
                'fba_id' => $bookingDepart->availability->fba_id,
                'fba_dept_time' => $bookingDepart->availability->fba_dept_time ?? $bookingDepart->availability->trip->fbt_dept_time,
                'fba_arrival_time' => $bookingDepart->availability->fba_arrival_time ?? $bookingDepart->availability->trip->fbt_arrival_time,
                'fb_image1' => url('storage/' . ltrim($bookingDepart->availability->trip->fastboat->fb_image1, '/')) ?? null,
                'cpn_name' => $bookingDepart->availability->trip->fastboat->company->cpn_name ?? null,
                'cpn_logo' => url('storage/' . ltrim($bookingDepart->availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                'fbo_adult_publish' => $bookingDepart->fbo_adult_publish,
                'fbo_child_publish' => $bookingDepart->fbo_child_publish
            ];

            $return = [
                'passenger' => $bookingReturn->fbo_passenger,
                'adult' => $bookingReturn->fbo_adult,
                'child' => $bookingReturn->fbo_child,
                'infant' => $bookingReturn->fbo_infant,
                'fba_id' => $bookingReturn->availability->fba_id,
                'fba_dept_time' => $bookingReturn->availability->fba_dept_time ?? $bookingReturn->availability->trip->fbt_dept_time,
                'fba_arrival_time' => $bookingReturn->availability->fba_arrival_time ?? $bookingReturn->availability->trip->fbt_arrival_time,
                'fb_image1' => url('storage/' . ltrim($bookingReturn->availability->trip->fastboat->fb_image1, '/')) ?? null,
                'cpn_name' => $bookingReturn->availability->trip->fastboat->company->cpn_name ?? null,
                'cpn_logo' => url('storage/' . ltrim($bookingReturn->availability->trip->fastboat->company->cpn_logo, '/')) ?? null,
                'fbo_adult_publish' => $bookingReturn->fbo_adult_publish,
                'fbo_child_publish' => $bookingReturn->fbo_child_publish
            ];
        }

        // Menyusun data untuk API response
        $data = [
            'bookingOneWay' => $oneway,
            'bookingDepart' => $depart,
            'bookingReturn' => $return,
        ];

        // Menentukan apakah tipe booking adalah One Way atau Round Trip
        if ($oneway && !$depart && !$return) {
            $type = 'One Way';
        } elseif ($depart && $return) {
            $type = 'Round Trip';
        } else {
            $type = 'Unknown';
        }

        // Response data API
        return response()->json([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'type' => $type,  // Mengembalikan tipe booking
            'fbo_order_id' => $contactId,
            'data' => $data,
        ], 200);
    }

    public function makeInvoice(Request $request)
    {
        try {
            // Fetch the contact and booking data
            $contactId = $request->contactId;
            $payment_method = $request->payment_method;
            $contact = Contact::findOrFail($contactId);

            $booking = BookingData::where('fbo_order_id', $contactId)->first();
            $bookingId = $booking->fbo_booking_id;
            $totalAmount = 0;

            if ($bookingId && (str_ends_with($bookingId, 'X'))) {
                $bookingOneWay = BookingData::where('fbo_order_id', $contactId)
                    ->where('fbo_booking_id', 'like', '%X')
                    ->first();

                if ($bookingOneWay) {
                    $totalAmount += $bookingOneWay->fbo_end_total;
                }
            } else {
                $bookingDepart = BookingData::where('fbo_order_id', $contactId)
                    ->where('fbo_booking_id', 'like', '%Y')
                    ->first();

                $bookingReturn = BookingData::where('fbo_order_id', $contactId)
                    ->where('fbo_booking_id', 'like', '%Z')
                    ->first();

                if ($bookingDepart) {
                    $totalAmount += $bookingDepart->fbo_end_total;
                }

                if ($bookingReturn) {
                    $totalAmount += $bookingReturn->fbo_end_total;
                }
            }

            if ($totalAmount <= 0) {
                return response()->json([
                    'error' => 'No valid bookings found or total amount is zero'
                ], 400);
            }

            // Prepare invoice data
            $invoiceData = [
                'external_id' => $contact->ctc_order_id,
                'amount' => $totalAmount,
                'payer_email' => $contact->ctc_email,
                'description' => $contact->ctc_note ?? 'Fastboat Booking',
                'payment_methods' => [$payment_method],
            ];

            // URL Endpoint Xendit
            $url = 'https://api.xendit.co/v2/invoices';

            // Send HTTP Request to Xendit API
            $response = Http::withBasicAuth(env('XENDIT_SECRET_KEY'), '')
                ->post($url, [
                    'external_id' => $invoiceData['external_id'],
                    'amount' => $invoiceData['amount'],
                    'payer_email' => $invoiceData['payer_email'],
                    'description' => $invoiceData['description'],
                    'payment_methods' => $invoiceData['payment_methods'],
                    'success_redirect_url' => url('/payment-success'),
                    'failure_redirect_url' => url('/payment-failure'),
                ]);

            if ($response->successful()) {
                $invoice = $response->json();

                // Update booking data with invoice information
                if ($bookingId && (str_ends_with($bookingId, 'X'))) {
                    if ($bookingOneWay) {
                        $bookingOneWay->fbo_transaction_id = $invoice['id'] ?? null;
                        $bookingOneWay->fbo_payment_method = 'Bank Transfer';
                        $bookingOneWay->save();
                    }
                } else {
                    if ($bookingDepart) {
                        $bookingDepart->fbo_transaction_id = $invoice['id'] ?? null;
                        $bookingDepart->fbo_payment_method = 'Bank Transfer';
                        $bookingDepart->save();
                    }

                    if ($bookingReturn) {
                        $bookingReturn->fbo_transaction_id = $invoice['id'] ?? null;
                        $bookingReturn->fbo_payment_method = 'Bank Transfer';
                        $bookingReturn->save();
                    }
                }

                return response()->json([
                    'invoice_url' => $invoice['invoice_url'],
                    'invoice_id' => $invoice['id']
                ]);
            } else {
                return response()->json([
                    'error' => 'Failed to create Xendit invoice',
                    'details' => $response->json()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Exception in creating Xendit invoice',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateTicketPdf($ticketId)
    {
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

        $data = [
            'name' => $dataTicket->contact->ctc_name,
            'email' => $dataTicket->contact->ctc_email,
            'phone' => $dataTicket->contact->ctc_phone,
            'note' => $dataTicket->contact->ctc_note,
            'fbo_booking_id' => $dataTicket->fbo_booking_id,
            'fbo_payment_status' => $dataTicket->fbo_payment_status,
            'fbo_trip_date' => $formattedTripDate,
            'fbo_checkin_point_address' => $dataTicket->checkPoint->fcp_address,
            'fbo_checkin_point_maps' => $dataTicket->checkPoint->fcp_maps,
            'fbo_pickup' => $dataTicket->fbo_pickup,
            'fbo_specific_pickup' => $dataTicket->fbo_specific_pickup,
            'fbo_contact_pickup' => $dataTicket->fbo_contact_pickup,
            'fbo_dropoff' => $dataTicket->fbo_dropoff,
            'fbo_specific_dropoff' => $dataTicket->fbo_specific_dropoff,
            'fbo_contact_dropoff' => $dataTicket->fbo_contact_dropoff,
            'cpn_name' => $dataTicket->trip->fastboat->company->cpn_name,
            'cpn_email' => $dataTicket->trip->fastboat->company->cpn_email,
            'cpn_phone' => $dataTicket->trip->fastboat->company->cpn_phone,
            'cpn_logo' => base64_encode(file_get_contents(Helpers\imagePath($dataTicket->trip->fastboat->company->cpn_logo))),
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

        $pdf = Pdf::loadView('ticket.gt', $data);
        return $pdf->output();
    }

    private function emailCustomer($fbo_id)
    {
        try {
            // Cari data booking
            $booking = BookingData::findOrFail($fbo_id);
            $contact = $booking->contact;

            if (!$contact) {
                throw new \Exception('Contact not found');
            }

            $fboBookingId = $booking->fbo_booking_id;
            $bookingType = substr($fboBookingId, -1);

            // Persiapkan variabel untuk konten PDF dan filename
            $pdfContents = [];
            $filenames = [];
            $paths = []; // Untuk menyimpan path file sementara yang dibuat
            $ticketData = []; // Array untuk menyimpan data tiket (bisa 1 atau 2 tiket)

            // Function untuk memformat data tiket
            $formatTicketData = function ($dataTicket) {
                $passengers = explode(';', $dataTicket->fbo_passenger);
                $passengerArray = [];

                foreach ($passengers as $passenger) {
                    $details = explode(',', $passenger);
                    if (count($details) === 4) {
                        $age = (int) $details[1];
                        if ($age > 13) {
                            $ageGroup = 'ADULT';
                        } elseif ($age >= 3 && $age <= 12) {
                            $ageGroup = 'CHILD';
                        } elseif ($age >= 0 && $age <= 2) {
                            $ageGroup = 'INFANT';
                        } else {
                            $ageGroup = 'UNKNOWN';
                        }

                        $passengerArray[] = [
                            'name' => $details[0],
                            'age' => $ageGroup,
                            'gender' => $details[2],
                            'nationality' => $details[3],
                        ];
                    }
                }

                $tripDate = new \DateTime($dataTicket->fbo_trip_date);
                $time = $dataTicket->availability->fba_dept_time ?? $dataTicket->trip->fbt_dept_time;
                $timeDateTime = new \DateTime($time);
                $arrivaltime = new \DateTime($dataTicket->fbo_arrival_time);
                $bookingDate = new \DateTime($dataTicket->created_at);

                $logoPath = Helpers\imagePath($dataTicket->trip->fastboat->company->cpn_logo);
                $logoBase64 = '';
                if (file_exists($logoPath)) {
                    $logoType = mime_content_type($logoPath);
                    $logoContent = file_get_contents($logoPath);
                    if ($logoContent !== false) {
                        $logoBase64 = 'data:' . $logoType . ';base64,' . base64_encode($logoContent);
                    }
                }

                $adult_currency = $dataTicket->fbo_adult_currency * $dataTicket->fbo_adult;
                $child_currency = $dataTicket->fbo_child_currency * $dataTicket->fbo_child;

                return [
                    'name' => $dataTicket->contact->ctc_name,
                    'email' => $dataTicket->contact->ctc_email,
                    'phone' => $dataTicket->contact->ctc_phone,
                    'note' => $dataTicket->contact->ctc_note,
                    'fbo_booking_id' => $dataTicket->fbo_booking_id,
                    'fbo_payment_status' => $dataTicket->fbo_payment_status,
                    'fbo_trip_date' => $tripDate->format('l, d M Y'),
                    'fbo_checkin_point_address' => $dataTicket->checkPoint->fcp_address,
                    'fbo_checkin_point_maps' => $dataTicket->checkPoint->fcp_maps,
                    'fbo_pickup' => $dataTicket->fbo_pickup,
                    'fbo_specific_pickup' => $dataTicket->fbo_specific_pickup,
                    'fbo_contact_pickup' => $dataTicket->fbo_contact_pickup,
                    'fbo_dropoff' => $dataTicket->fbo_dropoff,
                    'fbo_specific_dropoff' => $dataTicket->fbo_specific_dropoff,
                    'fbo_contact_dropoff' => $dataTicket->fbo_contact_dropoff,
                    'cpn_name' => $dataTicket->trip->fastboat->company->cpn_name,
                    'cpn_email' => $dataTicket->trip->fastboat->company->cpn_email,
                    'cpn_phone' => $dataTicket->trip->fastboat->company->cpn_phone,
                    'cpn_logo' =>  url('storage/' . $dataTicket->trip->fastboat->company->cpn_logo),
                    'fb_name' => $dataTicket->trip->fastboat->fb_name,
                    'fbt_name' => $dataTicket->trip->fbt_name,
                    'fbo_currency' => $dataTicket->fbo_currency,
                    'fbo_adult_currency' => $adult_currency,
                    'fbo_child_currency' => $child_currency,
                    'fbo_end_total_currency' => $dataTicket->fbo_end_total_currency,
                    'fbo_adult' => $dataTicket->fbo_adult,
                    'fbo_child' => $dataTicket->fbo_child,
                    'fbo_infant' => $dataTicket->fbo_infant,
                    'departure_port' => $dataTicket->trip->departure->prt_name_en,
                    'departure_island' => $dataTicket->trip->departure->island->isd_name,
                    'departure_time' => $timeDateTime->format('H:i'),
                    'arrival_port' => $dataTicket->trip->arrival->prt_name_en,
                    'arrival_island' => $dataTicket->trip->arrival->island->isd_name,
                    'arrival_time' => $arrivaltime->format('H:i'),
                    'passengers' => $passengerArray,
                    'logo_ticket' => base64_encode(file_get_contents(public_path('assets/images/logo-ticket.png'))),
                    'created_at' => $bookingDate->format('l, d M Y'),
                    'is_return' => substr($dataTicket->fbo_booking_id, -1) === 'Z', // Menandai apakah ini tiket return
                ];
            };

            // Load data untuk tiket pertama (X atau Y)
            $dataTicket = BookingData::with([
                'trip.fastboat',
                'trip.fastboat.company',
                'trip.departure',
                'trip.arrival',
                'contact',
                'availability'
            ])->findOrFail($fbo_id);

            $ticketData[] = $formatTicketData($dataTicket);

            // Generate PDF untuk tiket pertama
            $pdfContent = $this->generateTicketPdf($fbo_id);
            $filename = 'Ticket_' . $fboBookingId . '.pdf';
            $path = storage_path('app/temp/' . $filename);
            file_put_contents($path, $pdfContent);
            $pdfContents[] = $pdfContent;
            $filenames[] = $filename;
            $paths[] = $path;

            // Jika round trip (Y), tambahkan tiket return (Z)
            if ($bookingType === 'Y') {
                $returnBooking = BookingData::where('fbo_booking_id', substr($fboBookingId, 0, -1) . 'Z')
                    ->with([
                        'trip.fastboat',
                        'trip.fastboat.company',
                        'trip.departure',
                        'trip.arrival',
                        'contact',
                        'availability'
                    ])
                    ->first();

                if ($returnBooking) {
                    // Tambahkan data tiket return
                    $ticketData[] = $formatTicketData($returnBooking);

                    // Generate PDF untuk tiket return
                    $pdfContentReturn = $this->generateTicketPdf($returnBooking->fbo_id);
                    $filenameReturn = 'Ticket_' . $returnBooking->fbo_booking_id . '.pdf';
                    $pathReturn = storage_path('app/temp/' . $filenameReturn);
                    file_put_contents($pathReturn, $pdfContentReturn);
                    $pdfContents[] = $pdfContentReturn;
                    $filenames[] = $filenameReturn;
                    $paths[] = $pathReturn;
                }
            }

            // Kirim email dengan data lengkap
            Mail::to($contact->ctc_email)
                ->send(new CustomerMail($contact, [
                    'pdf_contents' => $pdfContents,
                    'filenames' => $filenames,
                    'ticket_data' => $ticketData,
                ]));

            // Hapus file PDF yang telah disimpan sementara
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }

            toast('Email has been delivered successfully!', 'success');
            return redirect()->route('data.view');
        } catch (\Exception $e) {
            // Hapus file PDF yang telah disimpan sementara meskipun terjadi error
            foreach ($paths as $path) {
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            toast('Failed to deliver email: ' . $e->getMessage(), 'error');
            return redirect()->route('data.view');
        }
    }

    public function emailCompany($fbo_id)
    {
        try {
            // Cari data booking
            $booking = BookingData::with([
                'trip.fastboat',
                'trip.fastboat.company',
                'trip.departure',
                'trip.arrival',
                'contact',
                'availability'
            ])->findOrFail($fbo_id);

            // Pastikan data perusahaan tersedia
            if (!$booking->availability?->trip?->fastboat?->company) {
                throw new \Exception('Company data not found');
            }

            $fboBookingId = $booking->fbo_booking_id;
            $bookingType = substr($fboBookingId, -1); // Y = single trip, Z = return trip
            $departCompany = $booking->availability->trip->fastboat->company;

            // Function untuk memformat data tiket
            $formatTicketData = function ($dataTicket) {
                $passengers = explode(';', $dataTicket->fbo_passenger);
                $passengerArray = [];

                foreach ($passengers as $passenger) {
                    $details = explode(',', $passenger);
                    if (count($details) === 4) {
                        $age = (int) $details[1];
                        $ageGroup = $age > 13 ? 'Adult' : ($age >= 3 && $age <= 12 ? 'Child' : ($age >= 0 ? 'Infant' : 'Unknown'));

                        $passengerArray[] = [
                            'name' => $details[0],
                            'age' => $ageGroup,
                            'gender' => $details[2],
                            'nationality' => $details[3],
                        ];
                    }
                }

                $tripDate = new \DateTime($dataTicket->fbo_trip_date);
                $time = $dataTicket->availability->fba_dept_time ?? $dataTicket->trip->fbt_dept_time;
                $timeDateTime = new \DateTime($time);
                $arrivaltime = new \DateTime($dataTicket->fbo_arrival_time);
                $bookingDate = new \DateTime($dataTicket->created_at);

                return [
                    'name' => $dataTicket->contact->ctc_name,
                    'email' => $dataTicket->contact->ctc_email,
                    'phone' => $dataTicket->contact->ctc_phone,
                    'note' => $dataTicket->contact->ctc_note,
                    'fbo_booking_id' => $dataTicket->fbo_booking_id,
                    'fbt_name' => $dataTicket->trip->fbt_name,
                    'fbo_trip_date' => $tripDate->format('l, d M Y'),
                    'departure_port' => $dataTicket->trip->departure->prt_name_en,
                    'arrival_port' => $dataTicket->trip->arrival->prt_name_en,
                    'departure_time' => $timeDateTime->format('H:i'),
                    'arrival_time' => $arrivaltime->format('H:i'),
                    'passengers' => $passengerArray,
                    'company_name' => $dataTicket->trip->fastboat->company->cpn_name,
                    'company_email' => $dataTicket->trip->fastboat->company->cpn_email,
                    'fastboat_name' => $dataTicket->trip->fastboat->fb_name,
                    'company_logo' => url('storage/' . $dataTicket->trip->fastboat->company->cpn_logo),
                ];
            };

            // Format data tiket keberangkatan
            $departTicketData = $formatTicketData($booking);
            $emailsSent = false;

            if ($bookingType === 'Y') {
                // Handle round trip
                $returnBooking = BookingData::where('fbo_booking_id', substr($fboBookingId, 0, -1) . 'Z')
                    ->with([
                        'trip.fastboat',
                        'trip.fastboat.company',
                        'trip.departure',
                        'trip.arrival',
                        'contact',
                        'availability'
                    ])
                    ->first();

                if ($returnBooking) {
                    $returnCompany = $returnBooking->availability->trip->fastboat->company;
                    $returnTicketData = $formatTicketData($returnBooking);

                    if ($departCompany->cpn_id === $returnCompany->cpn_id) {
                        // Same company - send one email
                        if ($departCompany->cpn_email_status == 1) {
                            Mail::to($departCompany->cpn_email)
                                ->send(new SupplierMail($booking, $departCompany->cpn_id, [$departTicketData, $returnTicketData]));
                            $emailsSent = true;
                            toast('Round trip email has been delivered to ' . $departCompany->cpn_name . '!', 'success');
                        } else {
                            toast('Email cannot be sent, company email status is inactive for ' . $departCompany->cpn_name, 'error');
                        }
                    } else {
                        // Different companies - send separate emails
                        $messages = [];

                        if ($departCompany->cpn_email_status == 1) {
                            Mail::to($departCompany->cpn_email)
                                ->send(new SupplierMail($booking, $departCompany->cpn_id, [$departTicketData]));
                            $emailsSent = true;
                            $messages[] = 'Departure email sent to ' . $departCompany->cpn_name;
                        } else {
                            $messages[] = 'Departure email could not be sent to ' . $departCompany->cpn_name . ' (inactive email status)';
                        }

                        if ($returnCompany->cpn_email_status == 1) {
                            Mail::to($returnCompany->cpn_email)
                                ->send(new SupplierMail($returnBooking, $returnCompany->cpn_id, [$returnTicketData]));
                            $emailsSent = true;
                            $messages[] = 'Return email sent to ' . $returnCompany->cpn_name;
                        } else {
                            $messages[] = 'Return email could not be sent to ' . $returnCompany->cpn_name . ' (inactive email status)';
                        }

                        toast(implode("\n", $messages), $emailsSent ? 'success' : 'error');
                    }
                }
            } else {
                // Single trip - send email
                if ($departCompany->cpn_email_status == 1) {
                    Mail::to($departCompany->cpn_email)
                        ->send(new SupplierMail($booking, $departCompany->cpn_id, [$departTicketData]));
                    $emailsSent = true;
                    toast('Email has been delivered to ' . $departCompany->cpn_name . '!', 'success');
                } else {
                    toast('Email cannot be sent, company email status is inactive for ' . $departCompany->cpn_name, 'error');
                }
            }

            if (!$emailsSent) {
                toast('No emails were sent due to inactive email status.', 'warning');
            }

            return redirect()->route('data.view');
        } catch (\Exception $e) {
            toast('Failed to deliver email: ' . $e->getMessage(), 'error');
            return redirect()->route('data.view');
        }
    }

    public function getCallback(Request $request)
    {
        $getToken = $request->headers->get('x-callback-token');
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');

        try {
            $contact = Contact::where('ctc_order_id', $request->external_id)->first();
            $orderId = $contact->ctc_id;
            $booking = BookingData::where('fbo_order_id', $orderId)->first();

            if (!$callbackToken) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Callback token Xendit not exist'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($getToken !== $callbackToken) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Invalid token callback'
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if ($booking) {
                if ($request->status === 'PAID') {
                    $fboBookingId = $booking->fbo_booking_id;
                    $bookingType = substr($fboBookingId, -1);

                    if ($bookingType === 'X') {
                        $booking->fbo_payment_status = 'paid';
                        $booking->save();
                    } elseif ($bookingType === 'Y') {
                        $returnBooking = BookingData::where('fbo_booking_id', substr($fboBookingId, 0, -1) . 'Z')->first();
                        if ($returnBooking) {
                            $booking->fbo_payment_status = 'paid';
                            $booking->save();

                            $returnBooking->fbo_payment_status = 'paid';
                            $returnBooking->save();
                        }
                    }

                    $this->emailCustomer($booking->fbo_id);
                    $this->emailCompany($booking->fbo_id);
                } else {
                    $booking->fbo_payment_status = 'failed';
                    $booking->save();
                }
            }

            return response()->json([
                'status' => Response::HTTP_OK,
                'message' => 'callback sent'
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
// data = ['passenger', 'trip', 'bookingdetails => Departure ?? Return | Adult, Child, Infant | subtotal | Pay Amount']
