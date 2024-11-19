<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pemesanan Tiket Kapal Cepat</title>
</head>
<body>
    <h2>Hello, {{ $contact->ctc_name }}</h2>
    <p>We are pleased to inform you that your ticket booking has been successfully processed! Below are the details of your booking:</p>
    <ul>
        <li><strong>Booking Number:</strong> {{ $contact->ctc_order_id }}</li>
        <li><strong>Customer Name:</strong> {{ $contact->ctc_name }}</li>
        <li><strong>Customer Email:</strong> {{ $contact->ctc_email }}</li>
        <li><strong>Customer Phone Number:</strong> {{ $contact->ctc_phone }}</li>
        <li><strong>Booking Time:</strong> {{ $contact->ctc_booking_date }} {{ $contact->ctc_booking_time }}</li>
    </ul>
    <p>Semoga perjalanan Anda menyenangkan!</p>
</body>
</html>
