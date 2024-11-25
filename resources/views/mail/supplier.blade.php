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
            justify-content: center;
            align-items: center;
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

        .title {
            background-color: #f8a03e;
            color: white;
            text-align: center;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }

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
            height: auto;
        }

        .info {
            flex: 1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            padding: 5px;
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
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h2>New Booking from Gilitransfers!</h2>
        </div>

        @foreach ($ticketData as $key => $ticket)
            <div class="title">
                <span>{{ $key === 0 ? 'Departure Trip' : 'Return Trip' }}</span>
            </div>

            <!-- Trip Details Section -->
            <div class="details">
                <div class="trip">
                    <div class="trip-logo">
                        <img src="{{ $ticket['company_logo'] }}" alt="{{ $ticket['company_name'] }} logo">
                    </div>
                    <div class="info">
                        <table>
                            <tr>
                                <th colspan="2">{{ $ticket['fbt_name'] }}</th>
                            </tr>
                            <tr>
                                <td>Booking ID</td>
                                <td>: {{ $ticket['fbo_booking_id'] }}</td>
                            </tr>
                            <tr>
                                <td>Date</td>
                                <td>: {{ $ticket['fbo_trip_date'] }}</td>
                            </tr>
                            <tr>
                                <td>Departure</td>
                                <td>: {{ $ticket['departure_port'] }}, {{ $ticket['departure_time'] }}</td>
                            </tr>
                            <tr>
                                <td>Arrival</td>
                                <td>: {{ $ticket['arrival_port'] }}, {{ $ticket['arrival_time'] }}</td>
                            </tr>
                            <tr>
                                <td>Boat Name</td>
                                <td>: {{ $ticket['fastboat_name'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="title">
                <span>Passenger Details</span>
            </div>

            <div class="details">
                <div class="passengers">
                    <table>
                        <tbody>
                            @if (isset($ticket['passengers']) && is_array($ticket['passengers']))
                                @foreach ($ticket['passengers'] as $index => $passenger)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $passenger['name'] }} ({{ $passenger['gender'] }})</td>
                                        <td>{{ $passenger['age'] }}</td>
                                        <td>{{ $passenger['nationality'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No passengers available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

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
