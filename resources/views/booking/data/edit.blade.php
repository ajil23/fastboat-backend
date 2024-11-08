@extends('admin.admin_master')
@section('admin')
<style>
    .nav-pills .nav-link.active {
        background-color: #131516 !important;
        color: #fff !important;
    }
</style>
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="col-xxl-3">
                <div class="card">
                    <div class="card-body">
                        <div class="position-relative text-center pb-3">
                            <div class="mt-3">
                                <h5 class="mb-1">{{ $data['fbo_booking_id'] }}</h5>
                                <p>Trip in <b>{{ $data['fbo_trip_date'] }}</b> from <b>{{ $data['departure_port'] }}
                                        ({{ $data['departure_time'] }})</b> to <b>{{ $data['arrival_port'] }}
                                        ({{ $data['arrival_time'] }})</b> with <b>{{ $data['adult'] }} Adult,
                                        {{ $data['child'] }} Child,
                                        {{ $data['infant'] }} Infant</b></p>
                            </div>
                        </div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-pills nav-justified" role="tablist">
                            <li class="nav-item">
                                <!-- Tambahkan class 'active' di sini untuk memastikan tab trip aktif -->
                                <a class="nav-link active" data-bs-toggle="tab" href="#trip" role="tab">
                                    <span>Trip</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#passenger" role="tab">
                                    <span>Passenger</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#shuttle" role="tab">
                                    <span>Shuttle</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#customer" role="tab">
                                    <span>Customer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#payment" role="tab">
                                    <span>Payment</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-xxl-9">
                <!-- Tab content -->
                <div class="tab-content">
                    <div class="tab-pane active" id="trip" role="tabpanel">
                        <form action="{{ route('data.update', $data['fbo_id']) }}" method="post">
                            @csrf
                            <input type="hidden" id="active_tab" name="active_tab" value="">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered text-center">
                                        <thead>
                                            <tr class="table-light">
                                                <th colspan="10">Before <span
                                                        class="text-danger">({{ $data['fbo_booking_id'] }})</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><b>Pub Adt</b></td>
                                                <td class="currency">IDR {{ $data['fbo_adult_publish'] }}</td>
                                                <td><b>Pub Adt Crr</b></td>
                                                <td class="currency">{{ $data['fbo_currency'] }}
                                                    {{ $data['fbo_adult_currency'] }}
                                                </td>
                                                <td><b>Nett Adt</b></td>
                                                <td class="currency">IDR {{ $data['fbo_adult_nett'] }}</td>
                                                <td><b>Kurs</b></td>
                                                <td class="currency">{{ $data['fbo_kurs'] }}</td>
                                                <td><b>Disc Tot</b></td>
                                                <td class="currency">IDR {{ $data['fbo_discount_total'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Pub Chd</b></td>
                                                <td class="currency">IDR {{ $data['fbo_child_publish'] }}</td>
                                                <td><b>Pub Chd Crr</b></td>
                                                <td class="currency">{{ $data['fbo_currency'] }}
                                                    {{ $data['fbo_child_currency'] }}
                                                </td>
                                                <td><b>Nett Chd</b></td>
                                                <td class="currency">IDR {{ $data['fbo_child_nett'] }}</td>
                                                <td><b>Disc/Pax</b></td>
                                                <td class="currency">IDR {{ $data['fbo_discount'] }}</td>
                                                <td><b>End Tot</b></td>
                                                <td class="currency">IDR {{ $data['fbo_end_total'] }}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Pub Tot</b></td>
                                                <td class="currency">IDR {{ $data['fbo_total_publish'] }}</td>
                                                <td><b>Pub Tot Crr</b></td>
                                                <td class="currency">{{ $data['fbo_currency'] }}
                                                    {{ $data['fbo_total_currency'] }}
                                                </td>
                                                <td><b>Nett Tot</b></td>
                                                <td class="currency">IDR {{ $data['fbo_total_nett'] }}</td>
                                                <td><b>Price Cut</b></td>
                                                <td class="currency">IDR {{ $data['fbo_price_cut'] }}</td>
                                                <td><b>End Tot Crr</b></td>
                                                <td class="currency">{{ $data['fbo_currency'] }}
                                                    {{ $data['fbo_end_total_currency'] }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- search trip depart -->
                            <div class="card custom-border-color">
                                <div class="p-1">
                                    <h5 class="font-size-16 mb-1 d-flex align-items-center justify-content-center"
                                        style="font-size: 14.8px; font-family: 'IBM Plex Sans', sans-serif;">
                                        <input type="checkbox" id="tripDepartCheckbox" class="me-2" value="active" name="depart">
                                        <label for="tripDepartCheckbox" class="mb-0 text-dark">Trip Depart</label>
                                    </h5>
                                </div>
                                <div class="collapse show" data-bs-parent="#addproduct-accordion" id="searchForm"
                                    style="display: none;">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_trip_date">Trip Date<span
                                                            class="text-danger">*</span></label>
                                                    <input type="date" class="form-control" id="fbo_trip_date"
                                                        name="fbo_trip_date">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_departure_port">Departure
                                                        Port<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="fbo_departure_port"
                                                        name="fbo_departure_port" disabled>
                                                        <option value="">Select Departure Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_arrival_port">Arrival
                                                        Port<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="fbo_arrival_port"
                                                        name="fbo_arrival_port" disabled>
                                                        <option value="">Select Arrival Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_departure_time">Time
                                                        Dept<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="fbo_departure_time"
                                                        name="fbo_departure_time" disabled>
                                                        <option value="">Select Time Dept</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <input type="hidden" value="{{ $data['fbo_id'] }}"
                                                        id="fbo_id" name="fbo_id">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hasil Pencarian -->
                            <div id="search-results" style="display: none;">
                                <div class="table-responsive">
                                    <h5 class="card-title">
                                        <center>Trip Results</center>
                                    </h5>
                                    <span class="form-check-label">
                                        <center id="result-date"></center>
                                    </span>
                                    <div class="container">
                                        <div class="row" id="result-container"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- detail hasil pencarian -->
                            <div class="card detail-trip" style="display: none;">
                                <div class="p-4 border-top custom-border-top-color">
                                    <!-- Hasil Pencarian -->
                                    <div id="search-results">
                                        <div class="table-responsive">
                                            <div class="selected-details">
                                                <p><strong>
                                                        <center><span id="selectedFastboat"></span></center>
                                                    </strong></p>
                                            </div>
                                            <table id="booking-data-table"
                                                class="table table-bordered table-centered align-middle table-nowrap mb-0 table-check">
                                                <thead>
                                                    <tr class="table-light">
                                                        <th>
                                                            <center>Publish Adult</center>
                                                        </th>
                                                        <th>
                                                            <center>Publish Child</center>
                                                        </th>
                                                        <th>
                                                            <center>Nett Adult</center>
                                                        </th>
                                                        <th>
                                                            <center>Nett Child</center>
                                                        </th>
                                                        <th>
                                                            <center>Discount</center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Hasil pencarian akan dimasukkan ke sini -->
                                                </tbody>
                                            </table>
                                            <br>
                                            <!-- perhitungan -->
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="price_adult">Adult Publish
                                                            (IDR)</label>
                                                        <input value="" class="form-control" id="price_adult_display" oninput="calculateTotal()">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="price_child">Child Publish
                                                            (IDR)</label>
                                                        <input value="" class="form-control" id="price_child_display" oninput="calculateTotal()">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fbo_end_total">End Total (IDR)</label>
                                                        <input value="" class="form-control" id="fbo_end_total_display" style="background-color:lightgray" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fbo_end_total_currency">End Total Currency (<span id="currencyCode"></span>)</label>
                                                        <input value="" class="form-control" id="fbo_end_total_currency_display"
                                                            style="background-color:lightgray" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" id="availability_id" name="availability_id" value="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="price_adult"
                                                            name="price_adult">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="price_child" name="price_child">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="fbo_end_total" name="fbo_end_total">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="fbo_end_total_currency" name="fbo_end_total_currency">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if(isset($direction) && $direction === 'roundtrip')
                            <!-- search trip return -->
                            <div class="card custom-border-color">
                                <div class="p-1">
                                    <h5 class="font-size-16 mb-1 d-flex align-items-center justify-content-center"
                                        style="font-size: 14.8px; font-family: 'IBM Plex Sans', sans-serif;">
                                        <input type="checkbox" id="tripReturnCheckbox" class="me-2" value="active" name="return">
                                        <label for="tripReturnCheckbox" class="mb-0 text-dark">Trip Return</label>
                                    </h5>
                                </div>
                                <div class="collapse show" id="tripReturnForm" style="display: none;">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_trip_date_return">Trip
                                                        Date<span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control"
                                                        id="fbo_trip_date_return" name="fbo_trip_date_return">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="fbo_departure_port_return">Departure Port<span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="fbo_departure_port_return"
                                                        name="fbo_departure_port_return" disabled>
                                                        <option value="">Select Departure Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_arrival_port_return">Arrival
                                                        Port<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="fbo_arrival_port_return"
                                                        name="fbo_arrival_port_return" disabled>
                                                        <option value="">Select Arrival Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_departure_time_return">Time
                                                        Return<span class="text-danger">*</span></label>
                                                    <select class="form-control" id="fbo_departure_time_return"
                                                        name="fbo_departure_time_return" disabled>
                                                        <option value="">Select Time Return</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="mb-3">
                                                    <input type="hidden" value="{{ $data['fbo_id'] }}"
                                                        id="fbo_id_return" name="fbo_id_return">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Return Trip Results -->
                            <div id="return-search-results" style="display: none;">
                                <div class="table-responsive">
                                    <h5 class="card-title">
                                        <center>Return Trip Results</center>
                                    </h5>
                                    <span class="form-check-label">
                                        <center id="return-result-date">Date of Return Trip</center>
                                    </span>
                                    <div class="container">
                                        <div class="row" id="return-result-container"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Return Trip Detail -->
                            <div class="card return-detail-trip" style="display: none;">
                                <div class="p-4 border-top custom-border-top-color">
                                    <div id="return-search-results">
                                        <div class="table-responsive">
                                            <div class="selected-details">
                                                <p><strong>
                                                        <center><span id="selectedFastboatReturn"></span></center>
                                                    </strong></p>
                                            </div>
                                            <table id="return-booking-data-table"
                                                class="table table-bordered table-centered align-middle table-nowrap mb-0 table-check">
                                                <thead>
                                                    <tr class="table-light">
                                                        <th>
                                                            <center>Publish Adult</center>
                                                        </th>
                                                        <th>
                                                            <center>Publish Child</center>
                                                        </th>
                                                        <th>
                                                            <center>Nett Adult</center>
                                                        </th>
                                                        <th>
                                                            <center>Nett Child</center>
                                                        </th>
                                                        <th>
                                                            <center>Discount</center>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Return trip search results will be added here -->
                                                </tbody>
                                            </table>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="return_price_adult">Adult Publish (IDR)</label>
                                                        <input type="text" class="form-control" id="price_adult_return_display" oninput="calculateReturnTotal()">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="return_price_child">Child Publish (IDR)</label>
                                                        <input type="text" class="form-control" id="price_child_return_display" oninput="calculateReturnTotal()">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="return_fbo_end_total">End Total
                                                            (IDR)</label>
                                                        <input type="text" class="form-control" id="fbo_end_total_return_display" style="background-color:lightgray" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="return_fbo_end_total_currency">End Total Currency (<span id="currencyCodeReturn"></span>)</label>
                                                        <input type="text" class="form-control" id="return_fbo_end_total_currency_display" style="background-color:lightgray" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" id="availability_id_return" name="availability_id_return" value="">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="return_price_adult"
                                                            name="return_price_adult">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="return_price_child" name="return_price_child">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="hidden" value="" class="form-control" id="return_fbo_end_total" name="return_fbo_end_total">
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <input type="text" class="form-control" id="return_fbo_end_total_currency" name="return_fbo_end_total_currency">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mb-4">
                                <div class="col text-end">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                            class="bx bx-x me-1"></i> Cancel</button>
                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i>
                                        Update</button>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="passenger" role="tabpanel">
                        <form action="{{ route('data.update', $data['fbo_id']) }}" method="post">
                            @csrf
                            <input type="hidden" id="active_tab" name="active_tab" value="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="p-4">
                                        <!-- Placeholder for Adult Information -->
                                        <div id="adult_info"></div>
                                        <!-- Placeholder for Child Information -->
                                        <div id="child_info"></div>
                                        <!-- Placeholder for Infant Information -->
                                        <div id="infant_info"></div>
                                        <!-- Hidden field to store combined passenger info -->
                                        <input type="hidden" id="fbo_passenger" name="fbo_passenger">
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col text-end">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                            class="bx bx-x me-1"></i> Cancel</button>
                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i>
                                        Update</button>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="shuttle" role="tabpanel">
                        <form action="{{ route('data.update', $data['fbo_id']) }}" method="post">
                            @csrf
                            <input type="hidden" id="active_tab" name="active_tab" value="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6 shuttle-inputs" id="pickup-inputs">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="fbo_pickup">Pickup
                                                                Area</label>
                                                            <select style="border-color: lightgray;"
                                                                class="form-control" id="fbo_pickup"
                                                                name="fbo_pickup">
                                                                @if (empty($data['fbo_pickup']))
                                                                <option value="">Select Option</option>
                                                                @endif
                                                                @foreach ($data['pickupAreas'] as $area)
                                                                <option value="{{ $area['name'] }}"
                                                                    data-pickup-meeting-point="{{ $area['pickup_meeting_point'] ?? '' }}"
                                                                    {{ isset($data['fbo_pickup']) && $data['fbo_pickup'] == $area['name'] ? 'selected' : '' }}>
                                                                    {{ $area['name'] }}
                                                                </option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">Phone</label>
                                                            <input type="text" style="border-color: lightgray;"
                                                                class="form-control" id=""
                                                                name="fbo_contact_pickup" placeholder="62XXXXXXXXXXX"
                                                                value="{{ $data['fbo_contact_pickup'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fbo_specific_pickup">Address
                                                            Pickup</label>
                                                        <textarea id="fbo_specific_pickup" name="fbo_specific_pickup" class="form-control" style="border-color: lightgray;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 shuttle-inputs" id="dropoff-inputs">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="fbo_dropoff">Dropoff
                                                                Area</label>
                                                            <select style="border-color: lightgray;"
                                                                class="form-control" id="fbo_dropoff"
                                                                name="fbo_dropoff">
                                                                @if (empty($data['fbo_dropoff']))
                                                                <option value="">Select Option</option>
                                                                @endif
                                                                @foreach ($data['dropoffAreas'] as $area)
                                                                <option value="{{ $area['name'] }}"
                                                                    data-dropoff-meeting-point="{{ $area['dropoff_meeting_point'] ?? '' }}"
                                                                    {{ isset($data['fbo_dropoff']) && $data['fbo_dropoff'] == $area['name'] ? 'selected' : '' }}>
                                                                    {{ $area['name'] }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">Phone</label>
                                                            <input type="text" style="border-color: lightgray;"
                                                                class="form-control" id=""
                                                                name="fbo_contact_dropoff" placeholder="62XXXXXXXXXXX"
                                                                value="{{ $data['fbo_contact_dropoff'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fbo_specific_dropoff">Address
                                                            Dropoff</label>
                                                        <textarea id="fbo_specific_dropoff" name="fbo_specific_dropoff" class="form-control"
                                                            style="border-color: lightgray;">{{ $data['fbo_specific_dropoff'] ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <div class="row mb-4">
                                <div class="col text-end">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                            class="bx bx-x me-1"></i> Cancel</button>
                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i>
                                        Update</button>

                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="customer" role="tabpanel">
                        <form action="{{ route('data.update', $data['fbo_id']) }}" method="post">
                            @csrf
                            <input type="hidden" id="active_tab" name="active_tab" value="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_name">Name</label>
                                                <input id="ctc_name" name="ctc_name" placeholder="Enter Name"
                                                    type="text" class="form-control" value="{{ $data['name'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_email">Email</label>
                                                <input id="ctc_email" name="ctc_email" placeholder="Enter Email"
                                                    type="email" class="form-control"
                                                    value="{{ $data['email'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_phone">Phone</label>
                                                <input id="ctc_phone" name="ctc_phone" placeholder="Enter Phone"
                                                    type="text" class="form-control"
                                                    value="{{ $data['phone'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_nationality">Nationality</label>
                                                <select id="ctc_nationality" class="form-control"
                                                    name="ctc_nationality">
                                                    <option value="{{ $data['nationality_id'] }}">
                                                        {{ $data['nationality_name'] }}
                                                    </option>
                                                    @foreach ($nationality as $item)
                                                    <option value="{{ $item->nas_id }}">{{ $item->nas_country }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_note">Note</label>
                                                <input id="ctc_note" name="ctc_note" placeholder="Enter Note"
                                                    type="text" class="form-control" value="{{ $data['note'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <div class="row mb-4">
                                <div class="col text-end">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                            class="bx bx-x me-1"></i> Cancel</button>
                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i>
                                        Update</button>

                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="tab-pane" id="payment" role="tabpanel">
                        <form action="{{ route('data.update', $data['fbo_id']) }}" method="post">
                            @csrf
                            <input type="hidden" id="active_tab" name="active_tab" value="">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-6">
                                                <label for="fbo_payment_method" class="form-label">Payment
                                                    Method</label>
                                                <select class="form-control" id="fbo_payment_method"
                                                    name="fbo_payment_method">
                                                    <option value="{{ $data['paymentMethod_value'] }}">
                                                        {{ $data['paymentMethod_name'] }}
                                                    </option>
                                                    @foreach ($payment as $item)
                                                    <option value="{{ $item->py_value }}">{{ $item->py_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="transaction-id" id="paypal_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="paypal_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="paypal_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Paypal Transaction ID" type="text"
                                                        class="form-control"
                                                        value="{{ $data['paymentMethod_value'] === 'paypal' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Midtrans Transaction ID -->
                                            <div class="transaction-id" id="midtrans_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="midtrans_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="midtrans_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Midtrans Transaction ID" type="text"
                                                        class="form-control"
                                                        value="{{ $data['paymentMethod_value'] === 'midtrans' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Bank Transfer Transaction ID -->
                                            <div class="transaction-id" id="bank_transfer_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="bank_transfer_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="bank_transfer_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Bank Transaction ID" type="text"
                                                        class="form-control"
                                                        value="{{ $data['paymentMethod_value'] === 'bank_transfer' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Cash Transaction ID -->
                                            <div class="transaction-id" id="cash_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cash_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="cash_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Recipient" type="text"
                                                        class="form-control"
                                                        value="{{ $data['paymentMethod_value'] === 'cash' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Agent Transaction ID -->
                                            <div class="transaction-id" id="agent_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="agent_transaction_id">Agent</label>
                                                    <select id="agent_transaction_id" name="fbo_transaction_id"
                                                        class="form-control">
                                                        <option
                                                            value="{{ $data['paymentMethod_value'] === 'agent' ? $data['transaction_id'] : '' }}">
                                                            {{ $data['paymentMethod_value'] === 'agent' ? $data['transaction_id'] : '' }}
                                                        </option>
                                                        <option value="Agen A">Agen A</option>
                                                        <option value="Agen B">Agen B</option>
                                                        <option value="Agen C">Agen C</option>
                                                        <option value="Agen D">Agen D</option>
                                                        <option value="Agen E">Agen E</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col text-end">
                                    <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                            class="bx bx-x me-1"></i> Cancel</button>
                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i>
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    @include('admin.components.footer')
</div>
@endsection
@section('script')
<script>
    // Payment
    $(document).ready(function() {
        // Fungsi untuk memformat angka dengan pemisah ribuan
        function formatNumber(value) {
            return value.toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // Memformat semua elemen dengan kelas 'currency'
        $('.currency').each(function() {
            let originalText = $(this).text();
            let currencySymbol = originalText.match(/[^\d.,]+/g) || [''];
            let text = originalText.replace(/[^\d.,]/g, '').trim();
            let value = parseFloat(text.replace(/\./g, '').replace(/,/g, '.'));

            if (!isNaN(value)) {
                let formattedNumber = formatNumber(value);
                $(this).text(currencySymbol.join(' ') + ' ' + formattedNumber);
            }
        });

        // Fungsi untuk menampilkan Transaction ID berdasarkan pilihan
        function showTransactionField(selectedMethod) {
            $('.transaction-id').hide();
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
        }

        showTransactionField($('#fbo_payment_method').val());

        $('#fbo_payment_method').on('change', function() {
            showTransactionField($(this).val());
        });

        // Fungsi untuk memvalidasi dan submit form
        $('form').submit(function(e) {
            var isValid = true; // Assume form is valid unless proven otherwise

            // Nonaktifkan semua input yang tidak terlihat (tersembunyi)
            $('.transaction-id').each(function() {
                if ($(this).is(':hidden')) {
                    $(this).find('input, select').prop('disabled', true);
                } else {
                    $(this).find('input, select').prop('disabled', false);
                }
            });

            // Validasi form sebelum submit
            // (Buat pengecekan validasi sesuai dengan kebutuhan Anda di sini)
            if ($('#ctc_name').val() === '') {
                isValid = false; // Misalnya, jika nama kosong
                alert('Please enter a valid name.');
            }

            if ($('#ctc_email').val() === '') {
                isValid = false; // Misalnya, jika email kosong
                alert('Please enter a valid email.');
            }

            // Lanjutkan submit form jika valid
            if (!isValid) {
                e.preventDefault(); // Jika form tidak valid, hentikan submit
            }
        });

        function updateInfo() {
            let adultCount = 0;
            let childCount = 0;
            let infantCount = 0;
            var passengers = @json($data['passengers']);

            passengers.forEach((passenger) => {
                let passengerType = passenger.age < 3 ? 'infant' : (passenger.age <= 12 ? 'child' :
                    'adult');
                let passengerIndex;

                if (passengerType === 'adult') {
                    adultCount++;
                    passengerIndex = adultCount;
                } else if (passengerType === 'child') {
                    childCount++;
                    passengerIndex = childCount;
                } else if (passengerType === 'infant') {
                    infantCount++;
                    passengerIndex = infantCount;
                }

                $(`#${passengerType}_info`).append(`
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="${passengerType}_name_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Name</label>
                            <input id="${passengerType}_name_${passengerIndex}" name="${passengerType}_name_[]" type="text" value="${passenger.name}" placeholder="Enter Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="${passengerType}_age_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Age</label>
                            <select id="${passengerType}_age_${passengerIndex}" name="${passengerType}_age_[]" class="form-control">
                                <option value="">Select Age</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="${passengerType}_gender_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Gender</label>
                            <select id="${passengerType}_gender_${passengerIndex}" name="${passengerType}_gender_[]" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Female" ${passenger.gender === 'Female' ? 'selected' : ''}>Female</option>
                                <option value="Male" ${passenger.gender === 'Male' ? 'selected' : ''}>Male</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="${passengerType}_nationality_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Nationality</label>
                            <select id="${passengerType}_nationality_${passengerIndex}" name="${passengerType}_nationality_[]" class="nationality-select">
                                <option value="">Select Nationality</option>
                                @foreach ($nationality as $item)
                                    <option value="{{ $item->nas_country }}" ${passenger.nationality === "{{ $item->nas_country }}" ? 'selected' : ''}>{{ $item->nas_country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            `);

                let ageSelect = $(`#${passengerType}_age_${passengerIndex}`);
                let ageRange = passengerType === 'infant' ? [0, 2] : (passengerType === 'child' ? [3,
                    12
                ] : [13, 49]);
                for (let age = ageRange[0]; age <= ageRange[1]; age++) {
                    ageSelect.append(
                        `<option value="${age}" ${passenger.age == age ? 'selected' : ''}>${age}</option>`
                    );
                }
                if (passengerType === 'adult') {
                    ageSelect.append(
                        '<option value="≧50" ${passenger.age == "≧50" ? "selected" : ""}>≧50</option>'
                    );
                }
            });

            savePassengerInfo();

            // Initialize Tom Select
            $('.nationality-select').each(function() {
                new TomSelect(this);
            });

            attachInputListeners();
        }

        function savePassengerInfo() {
            let passengerInfo = [];

            // Loop untuk adult
            $('#adult_info .row').each(function() {
                let name = $(this).find('[name="adult_name_[]"]').val();
                let age = $(this).find('[name="adult_age_[]"]').val();
                let gender = $(this).find('[name="adult_gender_[]"]').val();
                let nationality = $(this).find('[name="adult_nationality_[]"]').val();
                if (name && age && gender && nationality) {
                    passengerInfo.push(`${name},${age},${gender},${nationality}`);
                }
            });

            // Loop untuk child
            $('#child_info .row').each(function() {
                let name = $(this).find('[name="child_name_[]"]').val();
                let age = $(this).find('[name="child_age_[]"]').val();
                let gender = $(this).find('[name="child_gender_[]"]').val();
                let nationality = $(this).find('[name="child_nationality_[]"]').val();
                if (name && age && gender && nationality) {
                    passengerInfo.push(`${name},${age},${gender},${nationality}`);
                }
            });

            // Loop untuk infant
            $('#infant_info .row').each(function() {
                let name = $(this).find('[name="infant_name_[]"]').val();
                let age = $(this).find('[name="infant_age_[]"]').val();
                let gender = $(this).find('[name="infant_gender_[]"]').val();
                let nationality = $(this).find('[name="infant_nationality_[]"]').val();
                if (name && age && gender && nationality) {
                    passengerInfo.push(`${name},${age},${gender},${nationality}`);
                }
            });

            // Gabungkan semua data penumpang dengan titik koma sebagai pemisah antar penumpang
            let combinedInfo = passengerInfo.join(';');
            $('#fbo_passenger').val(combinedInfo);
        }

        function attachInputListeners() {
            $('#adult_info input, #adult_info select').on('input change', function() {
                savePassengerInfo();
            });
            $('#child_info input, #child_info select').on('input change', function() {
                savePassengerInfo();
            });
            $('#infant_info input, #infant_info select').on('input change', function() {
                savePassengerInfo();
            });
        }

        updateInfo();

        $('#fbo_adult, #fbo_child, #fbo_infant').on('change', function() {
            updateInfo();
        });
    });


    // shuttle pickup
    document.addEventListener('DOMContentLoaded', function() {
        const pickupSelect = document.getElementById('fbo_pickup');
        const specificPickupTextarea = document.getElementById('fbo_specific_pickup');

        // Mengisi textarea jika ada value default
        const defaultPickupId = "{{ $data['fbo_pickup'] }}";
        const defaultPickupMeetingPoint = "{{ $data['fbo_specific_pickup'] }}";

        if (defaultPickupId) {
            // Mengisi textarea dengan value default jika ada
            specificPickupTextarea.value = defaultPickupMeetingPoint;
            // Memilih opsi yang sesuai
            for (let option of pickupSelect.options) {
                if (option.value === defaultPickupId) {
                    option.selected = true;
                    break;
                }
            }
        }

        pickupSelect.addEventListener('change', function() {
            const selectedOption = pickupSelect.options[pickupSelect.selectedIndex];
            const pickupMeetingPoint = selectedOption.getAttribute('data-pickup-meeting-point');

            if (pickupMeetingPoint) {
                specificPickupTextarea.value = pickupMeetingPoint;
            } else {
                specificPickupTextarea.value = '';
            }
        });
    });

    // shuttle dropoff
    document.addEventListener('DOMContentLoaded', function() {
        const dropoffSelect = document.getElementById('fbo_dropoff');
        const specificDropoffTextarea = document.getElementById('fbo_specific_dropoff');

        // Mengisi textarea jika ada value default
        const defaultDropoffId = "{{ $data['fbo_dropoff'] }}";
        const defaultDropoffMeetingPoint = "{{ $data['fbo_specific_dropoff'] }}";

        if (defaultDropoffId) {
            // Mengisi textarea dengan value default jika ada
            specificDropoffTextarea.value = defaultDropoffMeetingPoint;
            // Memilih opsi yang sesuai
            for (let option of dropoffSelect.options) {
                if (option.value === defaultDropoffId) {
                    option.selected = true;
                    break;
                }
            }
        }

        dropoffSelect.addEventListener('change', function() {
            const selectedOption = dropoffSelect.options[dropoffSelect.selectedIndex];
            const dropoffMeetingPoint = selectedOption.getAttribute('data-dropoff-meeting-point');

            if (dropoffMeetingPoint) {
                specificDropoffTextarea.value = dropoffMeetingPoint;
            } else {
                specificDropoffTextarea.value = '';
            }
        });
    });

    $(document).ready(function() {
        console.log("jQuery is loaded and working.");
        // Event listener untuk perubahan checkbox
        $('#tripDepartCheckbox').on('change', function() {
            console.log("Checkbox found and changed");
            // Cek apakah checkbox dalam kondisi checked
            if ($(this).is(':checked')) {
                // Tampilkan form dan aktifkan input
                $('#searchForm').show();
                $('#searchForm').find('input, select').prop('disabled', false);
            } else {
                // Sembunyikan form dan nonaktifkan input
                $('#searchForm').hide();
                $('#searchForm').find('input, select').prop('disabled', true);
            }
        });
    });
</script>
<!-- script for trip depart -->
<script>
    $(document).ready(function() {
        $('#tripDepartCheckbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#searchForm').show();
                $('#searchForm').find('input, select').prop('disabled', false);
            } else {
                $('#searchForm').hide();
                $('#searchForm').find('input, select').prop('disabled', true).val('');

                // Sembunyikan dan kosongkan hasil pencarian dan detail
                resetSearchResults();
                hideTripDetails();
            }
        });

        function triggerSearch() {
            const tripDate = $('#fbo_trip_date').val();
            const departurePort = $('#fbo_departure_port').val();
            const arrivalPort = $('#fbo_arrival_port').val();
            const timeDept = $('#fbo_departure_time').val();
            const fbo_id = $('#fbo_id').val();

            if (tripDate && departurePort && arrivalPort && timeDept && fbo_id) {
                $.ajax({
                    url: "{{ route('data.searchTrip') }}",
                    type: 'GET',
                    data: {
                        fbo_trip_date: tripDate,
                        fbo_departure_port: departurePort,
                        fbo_arrival_port: arrivalPort,
                        fbo_departure_time: timeDept,
                        fbo_id: fbo_id
                    },
                    success: function(response) {
                        $('#result-container').empty();
                        $('#result-date').text(tripDate);
                        $('#search-results').show();

                        $.each(response.data, function(index, item) {
                            $('#result-container').append(`
                                <div class="col-md-6 col-sm-12 card-item">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input trip-checkbox" type="checkbox" data-item='${JSON.stringify(item)}' data-selected='${JSON.stringify(response.selected)}'>
                                                <label class="form-check-label">${item.fastboat_name}</label>
                                            </div>
                                            <div class="mt-3 pt-1">
                                                <div class="d-flex justify-content-between">
                                                    <p style="max-width: 70%; word-wrap: break-word;">${item.departure_port} (${item.departure_time}) - ${item.arrival_port} (${item.arrival_time})</p>
                                                    <p class="text-danger fw-bold">IDR ${formatToThousands(item.price_adult)}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });

                        $('.trip-checkbox').on('change', function() {
                            $('.trip-checkbox').not(this).prop('checked', false);

                            if ($(this).is(':checked')) {
                                const data = $(this).data('item');
                                const selectedData = $(this).data('selected');

                                showTripDetails(data, selectedData);
                            } else {
                                hideTripDetails();
                            }
                        });
                    },
                    error: function(error) {
                        console.log(error);
                        alert('Data tidak ditemukan');
                    }
                });
            }
        }

        // Fungsi untuk menambahkan format ribuan
        function formatToThousands(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Function untuk menampilkan detail trip dan memformat harga
        function showTripDetails(data, selected) {
            $('#booking-data-table tbody').html(`
                <tr>
                    <td><center>${formatToThousands(data.price_adult)}</center></td>
                    <td><center>${formatToThousands(data.price_child)}</center></td>
                    <td><center>${formatToThousands(data.price_adult_nett)}</center></td>
                    <td><center>${formatToThousands(data.price_child_nett)}</center></td>
                    <td><center>${formatToThousands(data.price_discount)}</center></td>
                </tr>
            `);

            $('#price_adult_display').val(`${formatToThousands(data.price_adult)}`);
            $('#price_child_display').val(`${formatToThousands(data.price_child)}`);
            $('#fbo_end_total_display').val(`${formatToThousands(data.total_price)}`);
            if (data.currency === 'IDR') {
                // If currency is IDR, display as is
                $('#fbo_end_total_currency_display').val(`${formatToThousands(data.total_price_currency)}`);
            } else {
                // If currency is not IDR, round up the value
                let roundedValue = Math.ceil(data.total_price_currency);
                $('#fbo_end_total_currency_display').val(`${formatToThousands(roundedValue)}`);
            }

            // Tetap menyimpan nilai asli tanpa format ribuan
            $('#price_adult').val(data.price_adult);
            $('#price_child').val(data.price_child);
            $('#fbo_end_total').val(data.total_price);
            $('#fbo_end_total_currency').val(data.total_price_currency);
            $('#currencyCode').text(data.currency_code);
            $('#availability_id').val(data.availability_id);

            const selectedDetail = `${selected.fastboat_name} (${selected.departure_port} -> ${selected.arrival_port} ${selected.departure_time})`;
            $('#selectedFastboat').text(selectedDetail);

            $('.detail-trip').show();
        }

        function hideTripDetails() {
            $('#booking-data-table tbody').empty();
            $('#price_adult').val('');
            $('#price_child').val('');
            $('#fbo_end_total').val('');
            $('#fbo_end_total_currency').val('');
            $('#currencyCode').text('');
            $('#fastboat_result').text('');
            $('#departure_result').text('');
            $('#arrival_result').text('');
            $('#selectedFastboat, #selectedDeparturePort, #selectedArrivalPort, #selectedDepartureTime').text('');
            $('.detail-trip').hide();
        }

        $('#fbo_trip_date, #fbo_departure_port, #fbo_arrival_port, #fbo_departure_time').on('change', triggerSearch);

        function resetSearchResults() {
            $('#search-results').hide();
            $('#result-container').empty();
        }

        $('#fbo_trip_date').change(function() {
            resetSearchResults();
            var tripDate = $(this).val();

            $.ajax({
                url: '/get-filtered',
                method: 'GET',
                data: {
                    fbo_trip_date: tripDate
                },
                success: function(response) {
                    $('#fbo_departure_port').empty().append('<option value="">Select Departure Port</option>');
                    $.each(response.fbo_departure_ports, function(index, port) {
                        $('#fbo_departure_port').append('<option value="' + port + '">' + port + '</option>');
                    });
                    $('#fbo_departure_port').prop('disabled', false);
                },
                error: function() {
                    console.error('Error fetching departure ports');
                }
            });
        });

        $('#fbo_departure_port').change(function() {
            $('#fbo_arrival_port').empty().append('<option value="">Select Arrival Port</option>');
            $('#fbo_departure_time').empty().append('<option value="">Select Time Dept</option>');

            var tripDate = $('#fbo_trip_date').val();
            var departurePort = $(this).val();

            $.ajax({
                url: '/get-filtered',
                method: 'GET',
                data: {
                    fbo_trip_date: tripDate,
                    fbo_departure_port: departurePort
                },
                success: function(response) {
                    $('#fbo_arrival_port').empty().append('<option value="">Select Arrival Port</option>');
                    $.each(response.fbo_arrival_ports, function(index, port) {
                        $('#fbo_arrival_port').append('<option value="' + port + '">' + port + '</option>');
                    });
                    $('#fbo_arrival_port').prop('disabled', false);
                },
                error: function() {
                    console.error('Error fetching arrival ports');
                }
            });
        });

        $('#fbo_arrival_port').change(function() {
            $('#fbo_departure_time').empty().append('<option value="">Select Time Dept</option>');

            var tripDate = $('#fbo_trip_date').val();
            var departurePort = $('#fbo_departure_port').val();
            var arrivalPort = $(this).val();

            $.ajax({
                url: '/get-filtered',
                method: 'GET',
                data: {
                    fbo_trip_date: tripDate,
                    fbo_departure_port: departurePort,
                    fbo_arrival_port: arrivalPort
                },
                success: function(response) {
                    $('#fbo_departure_time').empty().append('<option value="">Select Time Dept</option>');
                    $.each(response.fbo_departure_times, function(index, time) {
                        $('#fbo_departure_time').append('<option value="' + time + '">' + time + '</option>');
                    });
                    $('#fbo_departure_time').prop('disabled', false);
                },
                error: function() {
                    console.error('Error fetching departure times');
                }
            });
        });
    });
</script>

<!-- script for trip return -->
<script>
    $(document).ready(function() {
        // Initialize search results display and checkbox toggle for return trip form
        $('#tripReturnCheckbox').change(function() {
            if ($(this).is(':checked')) {
                $('#tripReturnForm').show();
                $('#tripReturnForm').find('input, select').prop('disabled', false);
            } else {
                // Hide form and disable inputs
                $('#tripReturnForm').hide();
                $('#tripReturnForm').find('input, select').prop('disabled', true).val(''); // Clear form inputs

                // Hide and clear search results
                resetReturnSearchResults();

                // Hide and clear return trip details
                hideReturnTripDetails();
            }
        });

        function formatToThousands(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Function to display search results on parameter changes
        $('#fbo_trip_date_return, #fbo_departure_port_return, #fbo_arrival_port_return, #fbo_departure_time_return')
            .on('change', triggerReturnSearch);

        function triggerReturnSearch() {
            const tripDateReturn = $('#fbo_trip_date_return').val();
            const departurePortReturn = $('#fbo_departure_port_return').val();
            const arrivalPortReturn = $('#fbo_arrival_port_return').val();
            const timeDeptReturn = $('#fbo_departure_time_return').val();
            const fbo_id_return = $('#fbo_id_return').val();

            if (tripDateReturn && departurePortReturn && arrivalPortReturn && timeDeptReturn && fbo_id_return) {
                $.ajax({
                    url: "{{ route('data.searchTripReturn') }}",
                    type: 'GET',
                    data: {
                        fbo_trip_date_return: tripDateReturn,
                        fbo_departure_port_return: departurePortReturn,
                        fbo_arrival_port_return: arrivalPortReturn,
                        fbo_departure_time_return: timeDeptReturn,
                        fbo_id_return: fbo_id_return
                    },
                    success: function(response) {
                        displayReturnResults(response.data, tripDateReturn);
                    },
                    error: function() {
                        alert('Data tidak ditemukan');
                    }
                });
            }
        }

        function displayReturnResults(data, tripDate) {
            $('#return-result-container').empty();
            $('#return-result-date').text(tripDate);
            $('#return-search-results').show();

            $.each(data, function(index, item) {
                $('#return-result-container').append(`
                    <div class="col-md-6 col-sm-12 card-item">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input return-trip-checkbox" type="checkbox" data-price_adult="${item.price_adult}" data-price_child="${item.price_child}" data-price_child_nett="${item.price_child_nett}" data-price_adult_nett="${item.price_adult_nett}" data-total_price="${item.total_price}" data-departure_port="${item.departure_port}" data-arrival_port="${item.arrival_port}" data-departure_time="${item.departure_time}" data-arrival_time="${item.arrival_time}" data-total_price_currency="${item.total_price_currency}" data-currency_code="${item.currency_code}" data-availability_id="${item.availability_id}" data-fastboat_name="${item.fastboat_name}">
                                    <label class="form-check-label">${item.fastboat_name}</label>
                                </div>
                                <div class="mt-3 pt-1">
                                    <div class="d-flex justify-content-between">
                                        <p>${item.departure_port} (${item.departure_time}) - ${item.arrival_port} (${item.arrival_time})</p>
                                        <p class="text-danger fw-bold">IDR ${formatToThousands(item.price_adult)}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });

            $('.return-trip-checkbox').on('change', function() {
                $('.return-trip-checkbox').not(this).prop('checked', false);
                if ($(this).is(':checked')) {
                    showReturnTripDetails($(this).data());
                } else {
                    hideReturnTripDetails();
                }
            });
        }

        function showReturnTripDetails(data) {
            $('#return-booking-data-table tbody').html(`
                <tr>
                    <td><center>IDR ${formatToThousands(data.price_adult)}</center></td>
                    <td><center>IDR ${formatToThousands(data.price_child)}</center></td>
                    <td><center>IDR ${formatToThousands(data.price_adult_nett)}</center></td>
                    <td><center>IDR ${formatToThousands(data.price_child_nett)}</center></td>
                    <td><center>0</center></td>
                </tr>
            `);

            $('#price_adult_return_display').val(`${formatToThousands(data.price_adult)}`);
            $('#price_child_return_display').val(`${formatToThousands(data.price_child)}`);
            $('#fbo_end_total_return_display').val(`${formatToThousands(data.total_price)}`);
            $('#return_fbo_end_total_currency_display').val(`${formatToThousands(data.total_price_currency)}`);
            if (data.currency === 'IDR') {
                // If currency is IDR, display as is
                $('#return_fbo_end_total_currency_display').val(`${formatToThousands(data.total_price_currency)}`);
            } else {
                // If currency is not IDR, round up the value
                let roundedValue = Math.ceil(data.total_price_currency);
                $('#return_fbo_end_total_currency_display').val(`${formatToThousands(roundedValue)}`);
            }

            $('#return_price_adult').val(data.price_adult);
            $('#return_price_child').val(data.price_child);
            $('#return_fbo_end_total').val(data.total_price);
            $('#return_fbo_end_total_currency').val(data.total_price_currency);
            $('#currencyCodeReturn').text(data.currency_code);
            $('#availability_id_return').val(data.availability_id);

            // Set selectedDetail using data only
            const selectedDetailReturn = `${data.fastboat_name} (${data.departure_port} -> ${data.arrival_port} ${data.departure_time})`;

            // Display the selected detail
            $('#selectedFastboatReturn').text(selectedDetailReturn);

            $('.return-detail-trip').show();
        }

        function hideReturnTripDetails() {
            $('#return-booking-data-table tbody').empty();
            $('#return_price_adult').val('');
            $('#return_price_child').val('');
            $('#return_fbo_end_total').val('');
            $('#return_fbo_end_total_currency').val('');
            $('#currencyCodeReturn').text('');
            $('#selectedFastboatReturn').text('');
            $('.return-detail-trip').hide();
        }

        function resetReturnSearchResults() {
            $('#return-search-results').hide();
            $('#return-result-container').empty();
        }

        // Fetch dropdown data based on trip date for departure ports
        $('#fbo_trip_date_return').change(function() {
            resetReturnSearchResults();
            var tripDateReturn = $(this).val();

            $.ajax({
                url: '/get-filtered-return',
                method: 'GET',
                data: {
                    fbo_trip_date_return: tripDateReturn
                },
                success: function(response) {
                    populateDropdown('#fbo_departure_port_return', response.fbo_departure_ports_return, 'Select Departure Port');
                },
                error: function() {
                    console.error('Error fetching departure ports for return');
                }
            });
        });

        // Fetch arrival ports based on selected departure port
        $('#fbo_departure_port_return').change(function() {
            resetArrivalAndTimeDropdowns();
            var tripDateReturn = $('#fbo_trip_date_return').val();
            var departurePortReturn = $(this).val();

            $.ajax({
                url: '/get-filtered-return',
                method: 'GET',
                data: {
                    fbo_trip_date_return: tripDateReturn,
                    fbo_departure_port_return: departurePortReturn
                },
                success: function(response) {
                    populateDropdown('#fbo_arrival_port_return', response.fbo_arrival_ports_return, 'Select Arrival Port');
                },
                error: function() {
                    console.error('Error fetching arrival ports for return');
                }
            });
        });

        // Fetch return times based on selected arrival port
        $('#fbo_arrival_port_return').change(function() {
            var tripDateReturn = $('#fbo_trip_date_return').val();
            var departurePortReturn = $('#fbo_departure_port_return').val();
            var arrivalPortReturn = $(this).val();

            $.ajax({
                url: '/get-filtered-return',
                method: 'GET',
                data: {
                    fbo_trip_date_return: tripDateReturn,
                    fbo_departure_port_return: departurePortReturn,
                    fbo_arrival_port_return: arrivalPortReturn
                },
                success: function(response) {
                    populateDropdown('#fbo_departure_time_return', response.fbo_departure_times_return, 'Select Time Return');
                },
                error: function() {
                    console.error('Error fetching departure times for return');
                }
            });
        });

        function populateDropdown(selector, options, defaultText) {
            $(selector).empty().append(`<option value="">${defaultText}</option>`);
            $.each(options, function(index, option) {
                $(selector).append(`<option value="${option}">${option}</option>`);
            });
            $(selector).prop('disabled', false);
        }

        function resetArrivalAndTimeDropdowns() {
            $('#fbo_arrival_port_return').empty().append('<option value="">Select Arrival Port</option>');
            $('#fbo_departure_time_return').empty().append('<option value="">Select Time Return</option>');
        }
    });

    $(document).ready(function() {
        // Handle active tab change
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            var activeTab = $(e.target).attr('href').substring(1); // Active tab ID
            $('input[name="active_tab"]').val(activeTab);
        });

        // Set initial active tab on page load
        var activeTab = $('.nav-pills .nav-link.active').attr('href').substring(1); // Active tab ID
        $('input[name="active_tab"]').val(activeTab);
    });
</script>

@endsection