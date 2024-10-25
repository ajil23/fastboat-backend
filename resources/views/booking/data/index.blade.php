@extends('admin.admin_master')
@section('admin')
    <style>
        .modal-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            width: 100%;
            line-height: 1.5;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="ms-auto">
                        <div class="btn-toolbar float-end" role="toolbar">
                            <div class="btn-group me-2 mb-2">
                                <a href="{{ route('data.add') }}" class="btn btn-dark w-100" id="btn-new-event"><i
                                        class="mdi mdi-plus"></i>Add</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <a href="#addproduct-img-collapse" class="text-body collbodyd" data-bs-toggle="collapse"
                        aria-haspopup="true" aria-expanded="false" aria-haspopup="true"
                        aria-controls="addproduct-img-collapse">
                        <div class="p-4">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="font-size-16 mb-1">Search Booking Data</h5>
                                </div>
                                <div class="flex-shrink-0">
                                    <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div id="addproduct-img-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                        <div class="p-4 border-top">
                            <form method="GET" action="{{ route('data.view') }}">
                                <div class="row">
                                    <!-- Order ID -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Order ID</label>
                                            <input type="text" name="order_id" id="search-order-id" class="form-control" value="{{ request('order_id') }}">
                                        </div>
                                    </div>

                                    <!-- Booking ID -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Booking ID</label>
                                            <input type="text" name="booking_id" id="search-booking-id" class="form-control" value="{{ request('booking_id') }}">
                                        </div>
                                    </div>

                                    <!-- Contact Name -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Contact Name</label>
                                            <input type="text" name="contact_name" id="search-contact-name" class="form-control" value="{{ request('contact_name') }}">
                                        </div>
                                    </div>

                                    <!-- Contact Email -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Contact Email</label>
                                            <input type="text" name="contact_email" id="search-contact-email" class="form-control" value="{{ request('contact_email') }}">
                                        </div>
                                    </div>

                                    <!-- Passanger Name -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Passanger Name</label>
                                            <input type="text" name="passenger_name" id="search-passenger-name" class="form-control" value="{{ request('passenger_name') }}">
                                        </div>
                                    </div>

                                    <!-- Company -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Company</label>
                                            <select name="company" id="search-company" class="form-control">
                                                <option value="">~All Company~</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->cpn_name }}"
                                                        {{ request('company') == $company->cpn_name ? 'selected' : '' }}>
                                                        {{ $company->cpn_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Departure Port -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Departure</label>
                                            <select name="departure" id="search-departure" class="form-control">
                                                <option value="">~All Departure Port~</option>
                                                @foreach ($departurePorts as $port)
                                                    <option value="{{ $port->prt_name_en }}" 
                                                        {{ request('departure') == $port->prt_name_en ? 'selected' : '' }}>
                                                        {{ $port->prt_name_en }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Arrival Port -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Arrival</label>
                                            <select name="arrival" id="search-arrival" class="form-control">
                                                <option value="">~All Arrival Port~</option>
                                                @foreach ($arrivalPorts as $item)
                                                        <option value="{{$item->prt_name_en}}" {{ old('arrival', request('arrival')) == $item->prt_name_en ? 'selected' : '' }}>{{$item->prt_name_en}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Transaction Status -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Transaction Status</label>
                                            <select name="fbo_transaction_status" id="search-transaction-status" class="form-control">
                                                <option value="">~All Transaction Status~</option>
                                                <option value="accepted" {{ request('fbo_transaction_status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="confirmed" {{ request('fbo_transaction_status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Source -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Source</label>
                                            <select name="fbo_source" id="search-source" class="form-control">
                                                <option value="">~All Source~</option>
                                                @foreach ($sources as $source)
                                                    <option value="{{ $source->fbo_source }}" 
                                                        {{ request('fbo_source') == $source->fbo_source ? 'selected' : '' }}>
                                                        {{ $source->fbo_source }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Range type</label>
                                            <select name="range_type" id="search-range-type" class="form-control">
                                                <option value="">~All Range~</option>
                                                <option value="trip_date" {{ request('range_type') === 'trip_date' ? 'selected' : '' }}>Trip Date</option>
                                                <option value="booking_date" {{ request('range_type') === 'booking_date' ? 'selected' : '' }}>Booking Date</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Date Range -->
                                    <div class="col-lg-3">
                                        <div class="mb-3">
                                            <label class="form-label">Date range</label>
                                            <input name="daterange" type="text" class="form-control flatpickr-input" id="daterange" placeholder="Input date range"
                                                value="">
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="position-relative text-center pb-3">
                                        <button type="submit" class="btn btn-outline-dark">
                                            <i class="mdi mdi-magnify"></i>&thinsp;Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center mb-2">
                                    <h5 class="card-title">Booking Data Table</h5>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                        <thead>
                                            <tr>
                                                <th>NO</th>
                                                <th>
                                                    <center>Detail Order</center>
                                                </th>
                                                <th>
                                                    <center>
                                                        <div>Status</div>
                                                        <div>Payment</div>
                                                    </center>
                                                </th>
                                                <th>
                                                    <center>
                                                        <div>Status</div>
                                                        <div>Transaction</div>
                                                    </center>
                                                </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($bookingData as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div>
                                                            <a href="javascript:void(0)"
                                                                class="badge {{ $item->fbo_source == 'backoffice' ? 'bg-primary' : ($item->fbo_source == 'api' ? 'bg-success' : 'bg-warning') }}"
                                                                data-bs-toggle="modal" data-bs-target="#passengerModal"
                                                                id="showDetail"
                                                                data-url="{{ route('data.show', $item->fbo_id) }}">
                                                                {{ $item->fbo_source == 'backoffice' ? 'Backoffice' : ($item->fbo_source == 'api' ? 'API' : 'Website') }}
                                                            </a>
                                                            <strong>{{ $item->contact->ctc_name }}</strong> ~
                                                            {{ $item->contact->ctc_email }} ~
                                                            {{ $item->contact->ctc_phone }} ~
                                                            {{ $item->created_at->format('H:i') }}
                                                        </div>
                                                        <div>
                                                            <strong>{{ $item->fbo_booking_id }}</strong> ~
                                                            <strong>{{ $item->trip->fastboat->fb_name }}</strong>
                                                            {{ $item->trip->departure->island->isd_name }} <span
                                                                class="text-danger">({{ \Carbon\Carbon::parse($item->fbo_trip_date)->format('d F Y') }}
                                                                {{ \Carbon\Carbon::parse($item->fbo_departure_time)->format('H:i') }})</span>
                                                            => {{ $item->trip->arrival->island->isd_name }} ~
                                                            <strong>{{ $item->fbo_adult + $item->fbo_child }} pax</strong>
                                                            ({{ $item->fbo_adult }} Adult, {{ $item->fbo_child }} Child,
                                                            {{ $item->fbo_infant }} Infant)
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <!-- Tombol untuk status paid/unpaid -->
                                                            @if ($item->fbo_transaction_status == 'remove')
                                                                <span class="text-muted">
                                                                    <i class="fas fa-ban"></i> Not Available
                                                                </span><br>
                                                            @else
                                                                @if ($item->fbo_payment_status == 'unpaid' || empty($item->fbo_payment_status))
                                                                    <a href="#" class="text-secondary btn-set-paid"
                                                                        data-id="{{ $item->fbo_id }}">
                                                                        <i class="fas fa-times-circle"></i>
                                                                        {{ $item->fbo_payment_status ?: 'unpaid' }}
                                                                    </a><br>
                                                                @else
                                                                    <span class="text-success payment-status"
                                                                        data-id="{{ $item->fbo_id }}">
                                                                        <i class="fas fa-check-circle"></i>
                                                                        {{ $item->fbo_payment_status }}
                                                                    </span><br>
                                                                @endif
                                                            @endif

                                                            <!-- Metode pembayaran -->
                                                            @if ($item->fbo_payment_method == 'paypal')
                                                                <span class="text-primary">Paypal</span>
                                                            @elseif($item->fbo_payment_method == 'midtrans')
                                                                <span class="text-primary">Midtrans</span>
                                                            @else
                                                                <span class="text-primary">Balance</span>
                                                            @endif
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <!-- Tombol Confirm/Unconfirm hanya jika status bukan 'remove' -->
                                                            @if ($item->fbo_transaction_status != 'remove' && $item->fbo_payment_status == 'paid')
                                                                <a href="#" class="btn-change-status"
                                                                    data-url="{{ route('data.status', $item->fbo_id) }}">
                                                                    <i
                                                                        class="fas {{ $item->fbo_transaction_status == 'confirmed' ? 'fa-sync-alt' : 'fa-check-circle' }}"></i>
                                                                    {{ $item->fbo_transaction_status == 'confirmed' ? 'Unconfirm' : 'Confirm' }}
                                                                </a><br>
                                                            @else
                                                                <span class="text-muted">
                                                                    <i class="fas fa-check-circle"></i> Confirm
                                                                </span><br>
                                                            @endif

                                                            <!-- Status display dengan tambahan kondisi 'remove' dan 'cancel' -->
                                                            <span
                                                                class="text-{{ $item->fbo_transaction_status == 'confirmed'
                                                                    ? 'success'
                                                                    : ($item->fbo_transaction_status == 'accepted'
                                                                        ? 'primary'
                                                                        : ($item->fbo_transaction_status == 'cancel'
                                                                            ? 'danger'
                                                                            : ($item->fbo_transaction_status == 'remove'
                                                                                ? 'secondary'
                                                                                : 'warning'))) }} transaction-status"
                                                                data-id="{{ $item->fbo_id }}">
                                                                <i
                                                                    class="fas {{ $item->fbo_transaction_status == 'confirmed'
                                                                        ? 'fa-check-circle'
                                                                        : ($item->fbo_transaction_status == 'cancel' || $item->fbo_transaction_status == 'remove'
                                                                            ? 'fa-times-circle'
                                                                            : 'fa-sync-alt') }}"></i>
                                                                {{ ucfirst($item->fbo_transaction_status) }}
                                                            </span>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <div class="dropstart">
                                                                <a class="text-muted dropdown-toggle font-size-18"
                                                                    role="button" data-bs-toggle="dropdown"
                                                                    aria-haspopup="true">
                                                                    <i class="mdi mdi-dots-horizontal"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item showDownloadTicket"
                                                                        href="#" data-id="{{ $item->fbo_id }}"
                                                                        id="showDownloadTicket"
                                                                        style="color: rgb(23, 162, 184);">
                                                                        <i class="mdi mdi-ticket"></i>
                                                                        Download Ticket
                                                                    </a><a class="dropdown-item" href="#"
                                                                        id="=" style="color: rgb(66, 133, 244);">
                                                                        <i class="mdi mdi-email-send"></i>
                                                                        Cust. Email
                                                                    </a><a class="dropdown-item" href="#"
                                                                        id="=" style="color: rgb(66, 133, 244);">
                                                                        <i class="mdi mdi-email-send"></i>
                                                                        Comp. Email
                                                                    </a>
                                                                    <a class="dropdown-item" href="javascript:void(0)"
                                                                        id="showWhatsapp"
                                                                        data-url="{{ route('data.whatsapp', $item->fbo_id) }}"
                                                                        style="color: rgb(37, 211, 102);">
                                                                        <i class="mdi mdi-whatsapp"></i>
                                                                        WhatsApp
                                                                    </a>
                                                                    <a class="dropdown-item text-secondary"
                                                                        href="javascript:void(0);" id="removeStatus"
                                                                        data-url="{{ route('data.updateStatus', $item->fbo_id) }}">
                                                                        <i class="mdi mdi-inbox-remove"></i> Remove
                                                                    </a>
                                                                    <a class="dropdown-item btn-cancel-transaction"
                                                                        data-id="{{ $item->fbo_id }}" href="#"
                                                                        style="color: rgb(234, 67, 53);">
                                                                        <i class="mdi mdi-cancel"></i>
                                                                        Cancel</a>
                                                                    <a class="dropdown-item btn-set-paid"
                                                                        data-id="{{ $item->fbo_id }}" href="#"
                                                                        style="color: rgb(255, 215, 0);">
                                                                        <i class="mdi mdi-credit-card"></i>
                                                                        Payment</a>
                                                                    <a class="dropdown-item" href="#"
                                                                        style="color: rgb(255, 90, 0);">
                                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                                        Edit</a>
                                                                </div>
                                                            </div>
                                                        </center>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        {{-- {{$company->links('pagination::bootstrap-5')}} --}}
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="passengerModal" tabindex="-1" role="dialog" aria-labelledby="passengerModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-title">
                        <h5 class="text-center">
                            <span class="text-danger" id="booking-id"></span> <span id="booking-fastboat"></span> <span
                                id="trip-date"></span><br>
                            <span id="route-info"></span>
                        </h5>
                    </div>
                    <div class="modal-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" id="passengers-tab" data-bs-toggle="tab"
                                    href="#passengers">Passengers</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="price-tab" data-bs-toggle="tab" href="#price">Price</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="logs-tab" data-bs-toggle="tab" href="#logs">Logs</a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3">
                            <!-- Passengers Tab -->
                            <div class="tab-pane fade show active" id="passengers">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>Old</th>
                                            <th>Nationality</th>
                                        </tr>
                                    </thead>
                                    <tbody id="passenger-list">
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <center>
                                        <strong>Passenger Note :</strong>
                                        <p id="passenger-info"></p>
                                        <p><strong>Checkin Point :</strong>
                                            <span id="chekin-point"></span>
                                        </p>
                                    </center>
                                </div>
                            </div>

                            <!-- Price Tab -->
                            <div class="tab-pane fade" id="price">
                                <!-- Add content for Price tab here -->
                                <!-- Table -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td style="background-color: lightgrey;"></td>
                                            <td style="background-color: lightgrey;">Adult</td>
                                            <td style="background-color: lightgrey;">Child</td>
                                            <td style="background-color: lightgrey;">Infant</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Qty</th>
                                            <td id="count-adult"></td>
                                            <td id="count-child"></td>
                                            <td id="count-infant"></td>
                                        </tr>
                                        <tr>
                                            <th>Price</th>
                                            <td>IDR <span id="adult-publish"></span></td>
                                            <td>IDR <span id="child-publish"></span></td>
                                            <td>IDR <span id="infant-publish"></span></td>
                                        </tr>
                                        <tr>
                                            <th>Sub Total</th>
                                            <td>IDR <span id="subtotal-adult"></span></td>
                                            <td>IDR <span id="subtotal-child"></span></td>
                                            <td>IDR 0</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Total Section -->
                                <table class="table table-bordered">
                                    <tbody>

                                        <tr>
                                            <td style="background-color: lightgrey;">
                                                <center>Total</center>
                                            </td>
                                            <td style="background-color: lightgrey;">
                                                <center>Discount/Pax</center>
                                            </td>
                                            <td style="background-color: lightgrey;">
                                                <center>Price Cut</center>
                                            </td>
                                            <td style="background-color: lightgrey;">
                                                <center>Discount Total</center>
                                            </td>
                                            <td style="background-color: lightgrey;">
                                                <center>End Total</center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <center>IDR <span id="total-publish"></span></center>
                                            </td>
                                            <td>
                                                <center>IDR <span id="discount"></span>x2pax</center>
                                            </td>
                                            <td>
                                                <center>IDR <span id="price-cut"></span></center>
                                            </td>
                                            <td>
                                                <center>IDR <span id="total-discount"></span></center>
                                            </td>
                                            <td>
                                                <center>IDR <span id="end-total"></span></center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="background-color: lightgrey;">
                                                <center>End Total Nett</center>
                                            </td>
                                            <td style="background-color: lightgrey;">
                                                <center>Profit</center>
                                            </td>
                                            <td style="background-color: lightgrey;" colspan="3">
                                                <center>Note</center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <center>IDR <span id="total-nett"></span></center>
                                            </td>
                                            <td><strong>
                                                    <center>IDR <span id="profit"></span></center>
                                                </strong></td>
                                            <td colspan="3">
                                                <center><span id="note"></span></center>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Balance ID:</td>
                                            <td colspan="4"><span id="transaction-id"></span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Logs Tab -->
                            <div class="tab-pane fade" id="logs">
                                <!-- Mail Status Section -->
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="background-color: lightgray;" colspan="4">
                                                <center>Send Mail & Ticket At</center>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <center>Mail Client</center>
                                            </th>
                                            <th>
                                                <center>Mail Admin</center>
                                            </th>
                                            <th>
                                                <center>Mail Supplier</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <center>Has not been sent</center>
                                            </td>
                                            <td>
                                                <center>Has not been sent</center>
                                            </td>
                                            <td>
                                                <center>Has not been sent</center>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- Logs Section -->
                                <div class="logs-section mt-3">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="background-color: lightgray;" colspan="4">
                                                    <center>Logs</center>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <center>User</center>
                                                </th>
                                                <th>
                                                    <center>Activity</center>
                                                </th>
                                                <th colspan="2">
                                                    <center>Date Time</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="logList">
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <td style="background-color: lightskyblue;" colspan="2">
                                                    <center>Before</center>
                                                </td>
                                                <td style="background-color: lightskyblue;" colspan="2">
                                                    <center>After</center>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Company </td>
                                                <td>: Idola Express</td>
                                                <td>Company </td>
                                                <td>: Idola Express</td>
                                            </tr>
                                            <tr>
                                                <td> Trip </td>
                                                <td> : Nusa Penida-Bali</td>
                                                <td> Trip </td>
                                                <td> : Nusa Penida-Bali</td>
                                            </tr>
                                            <tr>
                                                <td>Total_price </td>
                                                <td>: 220.000</td>
                                                <td>Total_price </td>
                                                <td>: 220.000</td>
                                            </tr>
                                            <tr>
                                                <td> Trip_date </td>
                                                <td>: 24-10-2024</td>
                                                <td> Trip_date </td>
                                                <td>: 24-10-2024</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Set Payment to Paid -->
        <div class="modal fade" id="setPaymentModal" tabindex="-1" aria-labelledby="setPaymentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setPaymentModalLabel">Set Payment to Paid</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="paymentForm" method="POST" action="{{ route('data.updatePayment') }}">
                        @csrf
                        <input type="hidden" name="fbo_id" id="fbo_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="fbo_payment_method" class="form-label">Payment Method</label>
                                <select class="form-control" id="fbo_payment_method" name="fbo_payment_method" required>
                                    <option value="" disabled selected>Select Payment Method</option>
                                    @foreach ($paymentMethod as $method)
                                        <option value="{{ $method->py_value }}">{{ $method->py_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Paypal Transaction ID -->
                            <div class="transaction-id" id="paypal_transaction_id" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label" for="paypal_transaction_id">Transaction ID</label>
                                    <input id="paypal_transaction_id" name="fbo_transaction_id"
                                        placeholder="Type Paypal Transaction ID" type="text" class="form-control">
                                </div>
                            </div>

                            <!-- Midtrans Transaction ID -->
                            <div class="transaction-id" id="midtrans_transaction_id" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label" for="midtrans_transaction_id">Transaction ID</label>
                                    <input id="midtrans_transaction_id" name="fbo_transaction_id"
                                        placeholder="Type Midtrans Transaction ID" type="text" class="form-control">
                                </div>
                            </div>

                            <!-- Bank Transfer Transaction ID -->
                            <div class="transaction-id" id="bank_transfer_transaction_id" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label" for="bank_transfer_transaction_id">Transaction ID</label>
                                    <input id="bank_transfer_transaction_id" name="fbo_transaction_id"
                                        placeholder="Type Bank Transaction ID" type="text" class="form-control">
                                </div>
                            </div>

                            <!-- Cash Transaction ID -->
                            <div class="transaction-id" id="cash_transaction_id" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label" for="cash_transaction_id">Transaction ID</label>
                                    <input id="cash_transaction_id" name="fbo_transaction_id"
                                        placeholder="Type Recipient" type="text" class="form-control">
                                </div>
                            </div>

                            <!-- Agent Transaction ID -->
                            <div class="transaction-id" id="agent_transaction_id" style="display: none;">
                                <div class="mb-3">
                                    <label class="form-label" for="agent_transaction_id">Agent</label>
                                    <select id="agent_transaction_id" name="fbo_transaction_id" class="form-control">
                                        <option value="">Select Agent</option>
                                        <option value="Agen A">Agen A</option>
                                        <option value="Agen B">Agen B</option>
                                        <option value="Agen C">Agen C</option>
                                        <option value="Agen D">Agen D</option>
                                        <option value="Agen E">Agen E</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-dark">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- WA Reservation modal-->
        <div class="modal fade" id="viewWhatsappModal" tabindex="-1" role="dialog"
            aria-labelledby="viewWhatsappModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewWhatsappModalTitle">
                            <i class="mdi mdi-whatsapp"></i>
                            WhatsApp Reservation For Booking ID <br> <span class="text-danger" id="wa-bookingid"></span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="whatsapp-message" style="background-color:#f0f4f7; padding:15px; border-radius:5px;"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-subtle-dark waves-effect waves-light" id="copyButton">
                            <i class="bx bx-clipboard"></i>
                        </button>
                        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-dark" id="send-reservation">
                            <i class="mdi mdi-send"></i>
                            Send
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Modal for Cancel Transaction -->
        <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelModalLabel">Cancel Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for canceling the transaction -->
                        <form id="cancelForm" method="POST" action="{{ route('data.cancelTransaction') }}">
                            @csrf
                            <!-- Refund options -->
                            <div class="form-group">
                                <label>Refund Options</label>
                                <input type="hidden" value="" name="fbo_id" id="order_id">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="fbo_payment_method"
                                        id="fullRefund" value="full_refund" required>
                                    <label class="form-check-label" for="fullRefund">Full Refund</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="fbo_payment_method"
                                        id="partialRefund" value="partial_refund">
                                    <label class="form-check-label" for="partialRefund">Partial Refund</label>
                                </div>
                                <!-- Partial refund input -->
                                <div class="form-group" id="partialRefundInput" style="display: none;">
                                    <label for="partial_refund_amount">Partial Refund Amount</label>
                                    <input type="text" class="form-control" name="partial_refund_amount"
                                        id="partial_refund_amount">
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="fbo_payment_method"
                                        id="fullCharge" value="full_charge">
                                    <label class="form-check-label" for="fullCharge">Full Charge</label>
                                </div>
                            </div>

                            <!-- Transaction details (display only) -->
                            <div class="form-group">
                                <label for="transaction_details">Transaction Details</label>
                                <input type="text" id="transaction_details" class="form-control" readonly>
                            </div>

                            <!-- Submit button -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-dark">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Download Ticket -->
        <div class="modal fade" id="TicketModal" tabindex="-1" role="dialog" aria-labelledby="TicketModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="TicketModalLabel">Download Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for canceling the transaction -->
                        <form id="ticketForm" method="GET" action="#">
                            @csrf
                            <!-- Format Ticket options -->
                            <div class="form-group">
                                <label>Format Ticket Options</label>
                                <input type="hidden" value="" name="ticket_id" id="ticket_id">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="downloadTicket" id="agen1"
                                        value="agen1">
                                    <label class="form-check-label" for="agen1">Agen 1</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="downloadTicket" id="agen2"
                                        value="agen2">
                                    <label class="form-check-label" for="agen2">Agen 2</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="downloadTicket"
                                        id="gilitransfers" value="gt">
                                    <label class="form-check-label" for="gilitransfers">Gilitransfers</label>
                                </div>
                            </div>
                            <br>
                            <!-- Submit button -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="printButton" class="btn btn-dark">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Page-content -->
    </div>
    </div>
    @include('admin.components.footer')
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.btn-change-status').on('click', function(e) {
                e.preventDefault(); // Mencegah aksi default link
                let url = $(this).data('url'); // Ambil URL dari atribut data-url

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to change the transaction status?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'dark',
                    cancelButtonColor: '#6e7881',
                    confirmButtonText: 'Yes, change it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url; // Redirect jika user konfirmasi
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.btn-set-paid').on('click', function(e) {
                e.preventDefault();
                let fboId = $(this).data('id'); // Ambil ID dari tombol yang diklik

                // Set value ID ke dalam hidden input modal
                $('#fbo_id').val(fboId);

                // Tampilkan modal
                $('#setPaymentModal').modal('show');
            });
        });

        $(document).ready(function() {
            // Saat payment method diubah
            $('#fbo_payment_method').on('change', function() {
                // Sembunyikan semua Transaction ID
                $('.transaction-id').hide();

                // Dapatkan nilai yang dipilih
                var selectedMethod = $(this).val();

                // Tampilkan Transaction ID berdasarkan pilihan
                if (selectedMethod === 'paypal') {
                    $('#paypal_transaction_id').show();
                } else if (selectedMethod === 'midtrans') {
                    $('#midtrans_transaction_id').show();
                } else if (selectedMethod === 'bank_transfer') {
                    $('#bank_transfer_transaction_id').show();
                } else if (selectedMethod === 'cash') {
                    $('#cash_transaction_id').show();
                } else if (selectedMethod === 'agent') {
                    $('#agent_transaction_id').show();
                }
            });

            // Saat form disubmit
            $('form').submit(function(e) {
                e.preventDefault(); // Mencegah submit form secara default
                var isValid = false; // Flag untuk mengecek validasi

                // Nonaktifkan semua input yang tidak terlihat (tersembunyi)
                $('.transaction-id').each(function() {
                    if ($(this).is(':hidden')) {
                        // Disable input atau select pada elemen yang disembunyikan
                        $(this).find('input, select').prop('disabled', true);
                    } else {
                        // Enable input atau select pada elemen yang terlihat
                        $(this).find('input, select').prop('disabled', false);
                        isValid = true; // Validasi berhasil jika ada input yang terlihat
                    }
                });

                this.submit();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '#showDetail', function() {
                var detailURL = $(this).data('url');
                $.get(detailURL, function(data) {

                    function formatNumber(number) {
                        return Number(number).toLocaleString(
                            'id-ID'
                        ); // 'id-ID' for Indonesian locale, use 'en-US' for English locale
                    }

                    // Fungsi untuk memformat tanggal menjadi tanggal-bulan-tahun
                    function formatTanggal(tanggalString) {
                        const bulanNama = [
                            "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                            "Jul", "Augt", "Sept", "Oct", "Nov", "Dec"
                        ];

                        const tanggal = new Date(
                            tanggalString); // Konversi string menjadi Date object
                        const hari = tanggal.getDate(); // Ambil hari
                        const bulan = bulanNama[tanggal.getMonth()]; // Ambil nama bulan
                        const tahun = tanggal.getFullYear(); // Ambil tahun

                        return `${hari} ${bulan} ${tahun}`; // Format tanggal-bulan-tahun
                    }

                    // Isi data ke dalam modal
                    $('#booking-id').text(data.fbo_booking_id);
                    $('#booking-fastboat').text(data.trip.fastboat.fb_name);
                    $('#trip-date').text(formatTanggal(data.fbo_trip_date));
                    $('#count-adult').text(data.fbo_adult);
                    $('#count-child').text(data.fbo_child);
                    $('#count-infant').text(data.fbo_infant);
                    $('#adult-publish').text(formatNumber(data.fbo_adult_publish));
                    $('#child-publish').text(formatNumber(data.fbo_child_publish));
                    $('#infant-publish').text(formatNumber(data.fbo_infant_publish));
                    $('#total-publish').text(formatNumber(data.fbo_total_publish));
                    $('#discount').text(formatNumber(data.fbo_discount));
                    $('#price-cut').text(formatNumber(data.fbo_price_cut));
                    $('#total-dicount').text(formatNumber(data.fbo_total_discount));
                    $('#total-nett').text(formatNumber(data.fbo_total_nett));
                    $('#end-total').text(formatNumber(data.fbo_end_total));
                    $('#profit').text(formatNumber(data.fbo_profit));
                    $('#note').text(formatNumber(data.fbo_refund));
                    $('#transaction-id').text(data.fbo_transaction_id);

                    // Logika untuk menghitung subtotal (count-adult * adult-publish)
                    let adultCount = parseInt(data.fbo_adult) || 0;
                    let adultPublish = parseFloat(data.fbo_adult_publish) || 0;
                    let subtotalAdult = adultCount * adultPublish;
                    // Tampilkan subtotal di modal
                    $('#subtotal-adult').text(formatNumber(subtotalAdult));

                    // Logika untuk menghitung subtotal (count-adult * adult-publish)
                    let childCount = parseInt(data.fbo_child) || 0;
                    let childPublish = parseFloat(data.fbo_child_publish) || 0;
                    let subtotalChild = childCount * childPublish;
                    // Tampilkan subtotal di modal
                    $('#subtotal-child').text(formatNumber(subtotalChild));

                    // Tampilkan informasi rute
                    let routeInfo = `
                    ${data.trip.departure_port} (${data.trip.departure_island}, ${data.trip.departure_time}) 
                    To ${data.trip.arrival_port} (${data.trip.arrival_island}, ${data.trip.arrival_time})
                `;
                    $('#route-info').html(routeInfo);

                    $('#passenger-list').empty();
                    // Loop untuk menambahkan data penumpang ke dalam tabel
                    data.trip.passengers.forEach(function(passenger, index) {
                        $('#passenger-list').append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${passenger.name}</td>
                            <td>${passenger.gender}</td>
                            <td>${passenger.age}</td>
                            <td>${passenger.nationality}</td>
                        </tr>
                    `);
                    });
                    $('#logList').empty();
                    data.logs.forEach(function(log) {
                        $('#logList').append(
                            `<tr>
                        <td><center>${log.user}</center></td>
                            <td><center>${log.activity}</center></td>
                            <td colspan="2"><center>${log.date}</center></td>
                        </tr>`
                        );
                    });
                    $('#passenger-info').text(data.contact.ctc_info);
                    $('#chekin-point').text(data.checkPoint.fcp_address);
                }).fail(function() {
                    alert('Gagal mengambil data. Silakan coba lagi.');
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#removeStatus').on('click', function(e) {
                e.preventDefault(); // Mencegah aksi default link
                let url = $(this).data('url'); // Ambil URL dari atribut data-url

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will set the status to 'remove'.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}' // Kirim CSRF token
                            },
                            success: function() {
                                location
                                    .reload(); // Langsung reload halaman setelah sukses
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'Failed to update the status. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '#showWhatsapp', function() {
                var whatsappUrl = $(this).data('url');
                $.get(whatsappUrl, function(data) {
                    $('#viewWhatsappModal').modal('show');
                    $('#wa-bookingid').text(data.fbo_booking_id);

                    var passengersList = data.passengers.map((passenger, index) => {
                        return `${index + 1}. ${passenger.name} (${passenger.gender}) ~ ${passenger.nationality} ~ ${passenger.age}`;
                    }).join('<br>');

                    var message = `
                        Dear Reservation Team ${data.company},<br><br>
                        Greetings from gilitransfers.<br><br>
                        Please Confirm Our Booking Below:<br>
                        <strong>Booking ID:</strong> ${data.fbo_booking_id}<br>
                        <strong>Route:</strong> ${data.departure_port} To ${data.arrival_port}<br>
                        <strong>Trip Date:</strong> ${data.fbo_trip_date}<br>
                        <strong>Time:</strong> ${(data.time)} <br><br>
                        <strong>Buyer Contact:</strong><br>
                        ${data.name} ~ ${data.email} ~ ${data.phone}<br><br>
                        <strong>Passengers:</strong><br>
                        ${passengersList}<br><br>
                        PAYMENT: ACC. GILITRANSFERS
                        <br><br>
                        Regards,<br>
                        Gilitransfers Team.
                    `;

                    // Masukkan pesan ke dalam modal
                    $('#whatsapp-message').html(message);

                    // Buat daftar penumpang untuk WhatsApp
                    var passengersListWA = data.passengers.map((passenger, index) => {
                        return `${index + 1}. ${passenger.name} (${passenger.gender}) ~ ${passenger.nationality} ~ ${passenger.age}`;
                    }).join('\n');

                    var whatsappText = [
                        `Dear Reservation Team ${data.company}`,
                        '',
                        'Greetings from gilitransfers.',
                        '',
                        'Please Confirm Our Booking Below:',
                        `*Booking ID:* ${data.fbo_booking_id}`,
                        `*Route:* ${data.departure_port} To ${data.arrival_port}`,
                        `*Trip Date:* ${data.fbo_trip_date}`,
                        `*Time:* ${data.time}`,
                        '',
                        '*Buyer Contact:*',
                        `${data.name} ~ ${data.email} ~ ${data.phone}`,
                        '',
                        '*Passengers:*',
                        passengersListWA,
                        '',
                        'PAYMENT: ACC. GILITRANSFERS',
                        '',
                        'Regards,',
                        'Gilitransfers Team.'
                    ].join('\n');

                    $('#send-reservation').off('click').on('click', function() {
                        var encodedText = encodeURIComponent(whatsappText);
                        var whatsappLink =
                            `https://api.whatsapp.com/send/?phone=${data.cpn_phone}&text=${encodedText}&type=phone_number&app_absent=0`;
                        window.open(whatsappLink, '_blank');
                    });
                });
            })
        });
    </script>

    {{-- Copy wa reservation --}}
    <script>
        document.getElementById('copyButton').addEventListener('click', function() {
            const message = document.getElementById('whatsapp-message').innerText;
            navigator.clipboard.writeText(message)
                .then(() => {
                    alert('Message copied to clipboard!');
                })
                .catch(err => {
                    console.error('Error copying text: ', err);
                });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.btn-cancel-transaction').on('click', function(e) {
                e.preventDefault();

                var transactionId = $(this).data('id');
                var transactionDetails = $(this).data('details');

                console.log('Transaction ID: ' + transactionId);
                console.log('Transaction Details: ' + transactionDetails);

                // Set the values in the modal
                $('#order_id').val(transactionId);
                $('#transaction_details').val(transactionDetails);

                // Show the modal
                $('#cancelModal').modal('show');
            });

            // Toggle partial refund input
            $('input[name="fbo_payment_method"]').change(function() {
                if ($('#partialRefund').is(':checked')) {
                    $('#partialRefundInput').show();
                } else {
                    $('#partialRefundInput').hide();
                }
            });

            // Format input as currency with thousand separator
            $('#partial_refund_amount').on('input', function() {
                // Remove non-numeric characters
                let value = $(this).val().replace(/[^0-9]/g, '');

                // Format as currency (thousand separator)
                if (value) {
                    $(this).val(Number(value).toLocaleString('id-ID'));
                } else {
                    $(this).val('');
                }
            });

            // Remove formatting when form is submitted
            $('#cancelForm').on('submit', function() {
                $('#partial_refund_amount').val(function() {
                    // Remove thousand separator for saving to the database
                    return $(this).val().replace(/,/g, '');
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.showDownloadTicket').on('click', function(e) {
                e.preventDefault();

                var ticketId = $(this).data('id');

                console.log('Transaction ID: ' + ticketId);

                // Set the values in the modal
                $('#ticket_id').val(ticketId);

                // Update form action with the ticket ID
                var actionUrl = '{{ route('data.viewTicket', ':ticketId') }}';
                actionUrl = actionUrl.replace(':ticketId', ticketId); // Ganti placeholder dengan ID tiket
                $('#ticketForm').attr('action', actionUrl);

                // Show the modal
                $('#TicketModal').modal('show');
            });
        });

        // Validate if a radio button is selected before submitting the form
        $('#printButton').on('click', function(e) {
            // Check if any radio button is selected
            if (!$("input[name='downloadTicket']:checked").val()) {
                // Prevent form submission
                e.preventDefault();
                
                // Show an alert or message indicating the validation error
                alert('Please select one of the ticket format options before submitting.');
            }
        });
    </script>

<script>
    flatpickr("#daterange", {
        mode: "range",
        dateFormat: "d-m-Y",
        disable: [
            function(date) {
                return !(date.getDate() % 100); // Custom disabling logic (optional)
            }
        ]
    });
</script>
@endsection
