<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Details</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        .container {
            width: 720px;
            /* Set the width to 720px */
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid #ddd;
        }

        /* Header Section */
        .header {
            display: flex;
            flex-direction: column;
            /* Menata teks secara vertikal */
            justify-content: center;
            /* Menempatkan teks di tengah secara vertikal */
            align-items: center;
            /* Menempatkan teks di tengah secara horizontal */
            background-color: #0066cc;
            color: white;
            padding: 15px 25px;
            font-size: 16px;
            text-align: center;
        }

        .header h2 {
            margin-bottom: 5px;
        }

        .header small {
            margin-top: 5px;
        }


        .header-item {
            text-align: center;
        }

        /* Trip Title Section */
        .title {
            background-color: #f8a03e;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }

        /* Trip Details Section */
        .details {
            padding: 30px;
            border-top: 1px solid #ddd;
        }

        .trip {
            display: flex;
            margin-bottom: 25px;
            padding-bottom: 20px;
        }

        .trip-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
        }

        .trip-logo img {
            width: 80px;
            /* Ganti ukuran lebar gambar menjadi 80px */
            height: auto;
            /* Menjaga proporsi gambar */
        }

        .info {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            padding: 2px;
            text-align: left;
        }

        table th {
            font-weight: bold;
        }

        table td {
            background-color: #fff;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1;
            font-size: 14px;
            color: #666;
        }

        .customer td {
            padding: 2px;
            /* Add padding inside table cells for better spacing */
        }



        @media (max-width: 768px) {
            .container {
                width: 100%;
                /* Full width on smaller screens */
                padding: 0 10px;
                /* Optional: Add some padding on smaller screens */
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h2>
                Thank you for booking with us!
            </h2>
        </div>

        <div class="title">
            <span>Customer</span>
        </div>

        <div class="details">
            <div class="customer">
                <table>
                    <tr>
                        <td>Order ID</td>
                        <td>: <b>{{ $contact->ctc_order_id }}</b></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>: {{ $contact->ctc_name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>: {{ $contact->ctc_email }}</td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>: {{ $contact->ctc_phone }}</td>
                    </tr>
                    <tr>
                        <td>Nationality</td>
                        <td>: {{ $contact->nationality->nas_country }}</td>
                    </tr>
                    <tr>
                        <td>Booking Date</td>
                        <td>: {{ $contact->ctc_booking_date }}, {{ $contact->ctc_booking_time }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="title">
            <span>Trip</span>
        </div>

        <!-- Trip Details Section -->
        <div class="details">
            @foreach ($ticket_data as $trip)
                <div class="trip {{ $trip['is_return'] ? 'return-ticket' : 'departure-ticket' }}">
                    <div class="trip-logo">
                        <img src="{{ $trip['cpn_logo'] }}" alt="{{ $trip['cpn_name'] }} Logo">
                    </div>
                    <div class="info">
                        <table>
                            <tr>
                                <th colspan="2">{{ $trip['fbt_name'] }}</th>
                            </tr>
                            <tr>
                                <td>Booking ID</td>
                                <td>: {{ $trip['fbo_booking_id'] }}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>: {{ $trip['fbo_trip_date'] }}</td>
                            </tr>
                            <tr>
                                <td>Departure</td>
                                <td>: {{ $trip['departure_port'] }}, {{ $trip['departure_island'] }}</td>
                            </tr>
                            <tr>
                                <td>Arrival</td>
                                <td>: {{ $trip['arrival_port'] }}, {{ $trip['arrival_island'] }}</td>
                            </tr>
                            <tr>
                                <td>Boat Name</td>
                                <td>: {{ $trip['fb_name'] }}</td>
                            </tr>
                            <tr>
                                <td>Note</td>
                                <td>: {{ $contact->ctc_note }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="title">
            <span>Passengers</span>
        </div>

        <div class="details">
            <div class="passengers">
                <table>
                    <tbody>
                        @if (isset($ticket_data[0]['passengers']) && is_array($ticket_data[0]['passengers']))
                            @foreach ($ticket_data[0]['passengers'] as $passenger)
                                <tr>
                                    <td>{{ $loop->iteration }}</td> <!-- Menampilkan urutan penumpang -->
                                    <td>{{ $passenger['name'] }} ({{ $passenger['gender'] }})</td>
                                    <td>{{ $passenger['age'] }}</td>
                                    <td>{{ $passenger['nationality'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($ticket_data[1]['passengers'] as $passenger)
                                <tr>
                                    <td>{{ $loop->iteration }}</td> <!-- Menampilkan urutan penumpang -->
                                    <td>{{ $passenger['name'] }} ({{ $passenger['gender'] }})</td>
                                    <td>{{ $passenger['age'] }}</td>
                                    <td>{{ $passenger['nationality'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <p><b>Customer Note:</b> Testing gan</p>
        </div>

        <div class="title">
            <span>Price</span>
        </div>

        <div class="details">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
                <tbody>
                    @php
                        $grandTotal = 0;
                        $currency = '';
                        
                        // Custom function untuk format number dengan titik
                        function formatNumber($number) {
                            return number_format($number, 0, ',', '.');
                        }
                    @endphp
                    
                    @foreach ($ticket_data as $trip)
                        @php
                            $grandTotal += $trip['fbo_end_total_currency'];
                            $currency = $trip['fbo_currency'];
                        @endphp
                        
                        <tr>
                            <td style="padding: 10px; border: 1px solid #ddd;" colspan="3"><b>{{ $trip['fbt_name'] }}</b></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px; border: 1px solid #ddd;">Adult</td>
                            <td style="text-align: center; border: 1px solid #ddd;">{{ $trip['fbo_adult'] }}</td>
                            <td style="text-align: right; border: 1px solid #ddd;">{{ $trip['fbo_currency'] }} {{ formatNumber($trip['fbo_adult_currency']) }}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px; border: 1px solid #ddd;">Child</td>
                            <td style="text-align: center; border: 1px solid #ddd;">{{ $trip['fbo_child'] }}</td>
                            <td style="text-align: right; border: 1px solid #ddd;">{{ $trip['fbo_currency'] }} {{ formatNumber($trip['fbo_child_currency']) }}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px; border: 1px solid #ddd;">Infant</td>
                            <td style="text-align: center; border: 1px solid #ddd;">{{ $trip['fbo_infant'] }}</td>
                            <td style="text-align: right; border: 1px solid #ddd;">{{ $trip['fbo_currency'] }} 0</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 10px; text-align: right; font-weight: bold; border: 1px solid #ddd;">Sub Total</td>
                            <td style="text-align: right; font-weight: bold; border: 1px solid #ddd;">{{ $trip['fbo_currency'] }} {{ formatNumber($trip['fbo_end_total_currency']) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" style="padding: 10px; text-align: right; font-size: 18px; font-weight: bold; color: #0066cc; border: 1px solid #ddd;">Total</td>
                        <td style="text-align: right; font-size: 18px; font-weight: bold; color: #0066cc; border: 1px solid #ddd;">{{ $currency }} {{ formatNumber($grandTotal) }}</td>
                    </tr>
                </tbody>
            </table>

            <p style="text-align: center; margin-top: 10px; font-size: 16px; color: #f8a03e;">
                <strong>Paid with <span style="color: #0066cc;">Xendit</span></strong>
            </p>
        </div>


        <!-- Footer Section -->
        <div class="footer">
            <strong>
                <a href="https://gilitransfers.com">Gilitransfers.com</a>
            </strong>
            <p>Phone:
                <a href="tel:+6281353304990">+62 81353304990</a>
            </p>
            <p>Email:
                <a href="mailto:reservation@gilitransfers.com">reservation@gilitransfers.com</a>
            </p>
        </div>
    </div>
</body>

</html>

{{-- mail/customer.blade.php
@foreach ($ticket_data as $ticket)
    <div class="ticket {{ $ticket['is_return'] ? 'return-ticket' : 'departure-ticket' }}">
        <h2>{{ $ticket['is_return'] ? 'Return Ticket' : 'Departure Ticket' }}</h2>
        
        {{-- Informasi Booking --}}
{{-- <div class="booking-info">
            <p>Booking ID: {{ $ticket['fbo_booking_id'] }}</p>
            <p>Booking Date: {{ $ticket['created_at'] }}</p>
        </div> --}}

{{-- Informasi Perjalanan --}}
{{-- <div class="trip-info">
            <h3>Trip Details</h3>
            <p>Date: {{ $ticket['fbo_trip_date'] }}</p>
            <p>From: {{ $ticket['departure_port'] }}, {{ $ticket['departure_island'] }}</p>
            <p>To: {{ $ticket['arrival_port'] }}, {{ $ticket['arrival_island'] }}</p>
            <p>Departure Time: {{ $ticket['departure_time'] }}</p>
            <p>Arrival Time: {{ $ticket['arrival_time'] }}</p>
        </div> --}}

{{-- Informasi Penumpang --}}
{{-- <div class="passenger-info">
            <h3>Passengers</h3>
            @foreach ($ticket['passengers'] as $passenger)
                <div class="passenger">
                    <p>Name: {{ $passenger['name'] }}</p>
                    <p>Type: {{ $passenger['age'] }}</p>
                    <p>Gender: {{ $passenger['gender'] }}</p>
                    <p>Nationality: {{ $passenger['nationality'] }}</p>
                </div>
            @endforeach
        </div>
    </div> --}}
{{-- @endforeach --}}
