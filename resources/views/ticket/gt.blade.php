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
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .container {
            padding-bottom: 20px;
        }

        .paid-stamp {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 150px;
            color: rgba(0, 128, 0, 0.2);
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
            padding: 10px;
            border-radius: 10px 10px 0 0;
            border-bottom: 2px solid #007bff;
            margin-bottom: 10px;
        }

        .header-logo img {
            width: 100px;
            height: auto;
        }

        .header-info {
            text-align: right;
        }

        .header-info h2 {
            font-size: 18px;
            margin: 0 0 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table th,
        table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 11px;
        }

        table th {
            background-color: #0056b3;
            color: white;
            font-weight: 500;
            font-size: 11px;
        }

        h3 {
            color: #0056b3;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .info ul {
            padding-left: 20px;
            color: #555;
            font-size: 11px;
        }

        .info ul li {
            margin-bottom: 3px;
        }

        .page-break {
            page-break-before: always;
            margin-top: 20px;
        }

        @media print {
            body {
                font-size: 10px;
                margin: 0;
                padding: 0;
            }

            .container {
                padding: 10px;
            }

            table th,
            table td {
                padding: 5px !important;
                font-size: 10px !important;
            }

            .header-logo img {
                width: 90px;
            }

            h3 {
                margin-bottom: 4px !important;
                font-size: 12px !important;
            }

            .header-info h2 {
                font-size: 16px !important;
            }

            .header-info p {
                font-size: 10px !important;
            }

            .info ul li {
                font-size: 10px !important;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="paid-stamp">PAID</div>

        <div class="header">
            <div class="header-logo">
                <img src="data:image/png;base64,{{ $logo_ticket }}" alt="GiliTransfers Logo">
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
                <td><b>{{ $fbo_booking_id }}</b></td>
                <th>Contact Name</th>
                <td>{{ $name }}</td>
            </tr>
            <tr>
                <th>Booking Date</th>
                <td>{{ $created_at }}</td>
                <th>Contact Phone</th>
                <td>{{ $phone }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>{{ $fbo_payment_status }}</td>
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
                @foreach ($passengers as $index => $passenger)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $passenger['gender'] == 'Male' ? 'Mr. ' : 'Mrs. ' }}{{ $passenger['name'] }}</td>
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
                    <img src="data:image/png;base64,{{ $cpn_logo }}" alt="logo company" style="height: 50px; width:50px;">
                    <br>
                    <strong>{{ $cpn_name }}</strong><br>
                    Phone: {{ $cpn_phone }}<br>
                    Email: {{ $cpn_email }}
                </td>
                <td class="center">
                    {{ $fbo_trip_date }}<br>
                    {{ $departure_time }}<br>
                    {{ $departure_port }}<br>
                    {{ $departure_island }}
                </td>
                <td class="center">
                    {{ $fbo_trip_date }}<br>
                    {{ $arrival_time }}<br>
                    {{ $arrival_port }}<br>
                    {{ $arrival_island }}
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
                <li>The duration of time we inform is only the estimated time and can change depending on sea conditions.</li>
            </ul>
        </div>
    </div>

    <div class="page-break"></div>

    <div class="container">
        <div class="header">
            <div class="header-logo">
                <img src="data:image/png;base64,{{ $logo_ticket }}" alt="GiliTransfers Logo">
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
                <td>2</td>
                <td>Mrs. Erna Ainul Khasanah</td>
                <td>Indonesia</td>
                <td>Adult</td>
            </tr>
        </table>
    </div>
</body>

</html>
