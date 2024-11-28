<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingData;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class XenditApiController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai contactId dari query parameter
        $contactId = $request->input('contactId');

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

            // Fetch booking data for depart, return, and one-way (if exists)
            $bookingDepart = BookingData::where('fbo_order_id', $contactId)
                ->where('fbo_booking_id', 'like', '%Y')
                ->first();

            $bookingReturn = BookingData::where('fbo_order_id', $contactId)
                ->where('fbo_booking_id', 'like', '%Z')
                ->first();

            $bookingOneWay = BookingData::where('fbo_order_id', $contactId)
                ->where('fbo_booking_id', 'like', '%X')
                ->first();

            // Calculate total amount based on booking types
            $totalAmount = 0;

            if ($bookingDepart) {
                $totalAmount += $bookingDepart->fbo_end_total;
            }

            if ($bookingReturn) {
                $totalAmount += $bookingReturn->fbo_end_total;
            }

            if ($bookingOneWay) {
                $totalAmount += $bookingOneWay->fbo_end_total;
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

                if ($bookingOneWay) {
                    $bookingOneWay->fbo_transaction_id = $invoice['id'] ?? null;
                    $bookingOneWay->fbo_payment_method = 'Bank Transfer';
                    $bookingOneWay->save();
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
}
// data = ['passenger', 'trip', 'bookingdetails => Departure ?? Return | Adult, Child, Infant | subtotal | Pay Amount']
