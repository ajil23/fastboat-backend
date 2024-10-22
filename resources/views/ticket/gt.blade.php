<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Ticket GiliTransfers</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 0;
            padding: 20px;
            position: relative;
        }

        .container {
            width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            position: relative;
        }

        /* Paid Stamp */
        .paid-stamp {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            /* Rotasi teks dengan sudut -30 derajat */
            font-size: 250px;
            color: rgba(0, 128, 0, 0.2);
            /* Warna hijau dengan transparansi */
            font-weight: bold;
            z-index: 1;
            pointer-events: none;
        }


        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #ffffff;
            color: #0056b3;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            border-bottom: 2px solid #007bff;
        }

        .header-logo img {
            width: 120px;
        }

        .header-info {
            text-align: right;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background-color: #0056b3;
            color: white;
            font-weight: 500;
        }

        table td {
            background-color: #f9f9f9;
        }

        .highlight {
            background-color: #007bff;
            color: white;
        }

        .center {
            text-align: center;
        }

        h3 {
            color: #0056b3;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .info ul {
            padding-left: 20px;
            color: #555;
        }

        .info ul li {
            margin-bottom: 5px;
        }

        @media print {
            body {
                font-family: 'Roboto', sans-serif !important;
                /* Ensure font consistency */
                background-color: white !important;
                /* Use white background for printing */
                color: #333 !important;
                /* Keep text color */
                margin: 0 !important;
                /* Remove margin */
                padding: 0 !important;
                /* Remove padding */
            }

            .container {
                width: 100% !important;
                /* Use full width for printing */
                margin: 0 !important;
                /* Reset margin */
                background-color: #fff !important;
                /* Background color for print */
                border-radius: 0 !important;
                /* Remove border radius */
                box-shadow: none !important;
                /* Remove shadow */
                padding: 20px !important;
                /* Maintain padding */
            }

            .paid-stamp {
                display: none !important;
                /* Hide the paid stamp */
            }

            .header {
                display: flex !important;
                /* Keep header layout */
                align-items: center !important;
                justify-content: space-between !important;
                background-color: #ffffff !important;
                color: #0056b3 !important;
                padding: 20px !important;
                border-bottom: 2px solid #007bff !important;
                /* Keep border for print */
            }

            .header-logo img {
                width: 120px;
                /* Keep logo size */
            }

            table {
                width: 100% !important;
                /* Full width */
                border-collapse: collapse !important;
                /* Collapse borders */
                margin: 0 !important;
                /* Reset margin */
                page-break-inside: avoid !important;
                /* Prevent page breaks inside tables */
            }

            table th,
            table td {
                padding: 10px !important;
                /* Adjust padding for print */
                border: 1px solid #ddd !important;
                /* Ensure borders are visible */
                text-align: left !important;
                /* Keep text alignment */
            }

            table th {
                background-color: #0056b3 !important;
                /* Keep header background */
                color: white !important;
                /* Keep header text color */
                font-weight: 500 !important;
                /* Ensure font weight */
            }

            table td {
                background-color: #f9f9f9 !important;
                /* Maintain cell background */
                color: #333 !important;
                /* Ensure cell text color */
            }

            .highlight {
                background-color: #007bff !important;
                /* Keep highlight color */
                color: white !important;
                /* Ensure text is white */
            }

            .center {
                text-align: center !important;
                /* Center text for printing */
            }

            h3 {
                color: #0056b3 !important;
                /* Keep header color */
                margin-bottom: 10px !important;
                /* Maintain margin */
                font-size: 18px !important;
                /* Keep font size */
            }

            .info ul {
                padding-left: 0 !important;
                /* Remove padding from list for print */
                color: #555 !important;
                /* Keep text color */
            }

            .info ul li {
                margin-bottom: 5px !important;
                /* Maintain list item spacing */
            }

            button {
                display: none !important;
                /* Hide print button */
            }
        }
    </style>
</head>

<body>
    {{-- <button onclick="window.print()">Print this page</button> --}}

    <div class="container">
        <div class="paid-stamp">PAID</div> <!-- Stempel "Paid" ditambahkan di sini -->

        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('assets/images/logo-ticket.png') }}" alt="GiliTransfers Logo">
            </div>
            <div class="header-info">
                <h2>E-TICKETS</h2>
                <p>Fast Boat • Tours • Car Transfer</p>
                <p>Phone: +62 81353304990 | Email: reservation@gilitransfers.com</p>
            </div>
        </div>


        <table class="booking-details">
            <tr>
                <th>Booking ID</th>
                <td><b>{{ $data['fbo_booking_id'] }}</b></td>
                <th>Contact Name</th>
                <td>{{ $data['name'] }}</td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ $data['created_at'] }}</td>
                <th>Contact Phone</th>
                <td>{{ $data['phone'] }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>{{ $data['fbo_payment_status'] }}</td>
            </tr>
        </table>

        <h3>Passengers</h3>
        <table class="passengers">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Country</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['passengers'] as $index => $passenger)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        @if ($passenger['gender'] == 'Male')
                            <td>Mr. {{ $passenger['name'] }}</td>
                        @else
                            <td>Mrs. {{ $passenger['name'] }}</td>
                        @endif
                        <td>{{ $passenger['nationality'] }}</td>
                        <td>{{ $passenger['age'] }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>


        <h3>Trip Details</h3>
        <table class="trip-details">
            <tr class="highlight">
                <th>Boat Info</th>
                <th>Departure</th>
                <th>Arrival</th>
            </tr>
            <tr>
                <td>
                    <img src="{{ asset('storage/' . $data['cpn_logo']) }}" alt="logo company"
                        style="height: 60px; width:60px;">
                    <br>
                    <strong>{{ $data['cpn_name'] }}</strong><br>
                    Phone: {{ $data['cpn_phone'] }}<br>
                    Email: {{ $data['cpn_email'] }}
                </td>
                <td class="center">
                    {{ $data['fbo_trip_date'] }}<br>
                    {{ $data['departure_time'] }}<br>
                    {{ $data['departure_port'] }}<br>
                    {{ $data['departure_island'] }}
                </td>
                <td class="center">
                    {{ $data['fbo_trip_date'] }}<br>
                    {{ $data['arrival_time'] }}<br>
                    {{ $data['arrival_port'] }}<br>
                    {{ $data['arrival_island'] }}
                </td>
            </tr>
            <tr>
                <th>Pick Up Location</th>
                <th>Drop Off Location</th>
                <th>Note</th>
            </tr>
            <tr>
                <td>ph:</td>
                <td>ph:</td>
                <td></td>
            </tr>
        </table>

        <div class="info">
            <h3>Information</h3>
            <ul>
                <li>Please bring your e-ticket and identity card for check-in at the port.</li>
                <li>Please kindly check-in at least 1 hour before departure time.</li>
                <li>The duration of time we inform is only the estimated time and can change depending on sea
                    conditions.</li>
            </ul>
        </div>

    </div>
    <div class="container">
        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('assets/images/logo-ticket.png') }}" alt="GiliTransfers Logo">
            </div>
            <div class="header-info">
                <h2>E-TICKETS</h2>
                <p>Fast Boat • Tours • Car Transfer</p>
                <p>Phone: +62 81353304990 | Email: reservation@gilitransfers.com</p>
            </div>
        </div>
        <h3>Checkin Point</h3>
        <table class="checkin">
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Country</th>
                <th>Type</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Mr. Muhammad Haqqul Yaqin</td>
                <td>Indonesia</td>
                <td>Adult</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Mrs. Erna Ainul Khasanah</td>
                <td>Indonesia</td>
                <td>Adult</td>
            </tr>
        </table>
    </div>

</body>

</html>
