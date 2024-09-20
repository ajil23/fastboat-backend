@extends('admin.admin_master')
@section('admin')
<style>
    .custom-border-color {
        border-color: lightgray;
        /* Ganti dengan warna yang diinginkan */
    }

    .custom-border-top-color {
        border-top-color: black;
    }
</style>

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{ route('data.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div id="addproduct-accordion" class="custom-accordion">
                            <div class="card custom-border-color">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Payment</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Name</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Name"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Email</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Email"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Phone</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Phone"
                                                        type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="nationality">Nationality</label>
                                                    <select id="nationality" name="nationality" required>
                                                        <option value="">Select Nationality</option>
                                                        @foreach ($nationality as $item)
                                                        <option value="{{ $item->nas_id }}">{{ $item->nas_country }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="currency">Currency</label>
                                                    <select id="currency" name="currency" required>
                                                        <option value="">Select Currency</option>
                                                        @foreach ($currency as $item)
                                                        <option value="{{ $item->cy_id }}"
                                                            data-rate="{{ $item->cy_rate }}"
                                                            data-code="{{ $item->cy_code }}">
                                                            {{ $item->cy_name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Payment Status</label>
                                                    <input id="fb_name" name="fb_name"
                                                        placeholder="Enter Payment Status" type="text"
                                                        class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="payment_method">Payment
                                                        Method</label>
                                                    <select id="payment_method" name="payment_method"
                                                        class="form-control" required>
                                                        <option value="">Select Payment Method</option>
                                                        <option value="paypal">Paypal</option>
                                                        <option value="midtrans">Midtrans</option>
                                                        <option value="bank_transfer">Bank Transfer</option>
                                                        <option value="pak_anang">Pak Anang</option>
                                                        <option value="pay_on_port">Pay on Port (collect)</option>
                                                        <option value="cash">Cash</option>
                                                        <option value="agent">Agent</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- paypal -->
                                            <div class="col-md-3 transaction-id" id="paypal_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="paypal_tid">Transaction ID</label>
                                                    <input id="paypal_tid" name="paypal_tid"
                                                        placeholder="Type Paypal Transaction ID" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <!-- midtrans -->
                                            <div class="col-md-3 transaction-id" id="midtrans_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="midtrans_tid">Transaction
                                                        ID</label>
                                                    <input id="midtrans_tid" name="midtrans_tid"
                                                        placeholder="Type Midtrans Transaction ID" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <!-- bank transfer -->
                                            <div class="col-md-3 transaction-id" id="bank_transfer_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="bank_transfer_tid">Transaction
                                                        ID</label>
                                                    <input id="bank_transfer_tid" name="bank_transfer_tid"
                                                        placeholder="Type Bank Transaction ID" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <!-- cash -->
                                            <div class="col-md-3 transaction-id" id="cash_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cash_tid">Transaction ID</label>
                                                    <input id="cash_tid" name="cash_tid"
                                                        placeholder="Type Recipient" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <!-- agent -->
                                            <div class="col-md-3 transaction-id" id="agent_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="agent_tid">Transaction ID</label>
                                                    <input id="agent_tid" name="agent_tid"
                                                        placeholder="Agen Transaction ID" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-border-color">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Customer</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="adult_count">Adult</label>
                                                    <input id="adult_count" name="adult_count" type="number"
                                                        class="form-control" value="1" min="1" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="child_count">Child</label>
                                                    <input id="child_count" name="child_count" type="number"
                                                        class="form-control" value="0" min="0" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="infant_count">Infant</label>
                                                    <input id="infant_count" name="infant_count" type="number"
                                                        class="form-control" value="0" min="0" required>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Placeholder for Adult Information -->
                                        <div id="adult_info"></div>
                                        <!-- Placeholder for Child Information -->
                                        <div id="child_info"></div>
                                        <!-- Placeholder for Infant Information -->
                                        <div id="infant_info"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-border-color">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Trip Depart</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <!-- Form Pencarian -->
                                        <form id="searchForm">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="trip_date">Trip Date</label>
                                                        <input type="date" class="form-control" id="trip_date"
                                                            name="trip_date">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="departure_port">Departure
                                                            Port</label>
                                                        <select class="form-control" id="departure_port"
                                                            name="departure_port">
                                                            <option value="">Select Departure Port</option>
                                                            @foreach ($availability as $item)
                                                            <option
                                                                value="{{ $item->trip->departure->prt_name_en }}">
                                                                {{ $item->trip->departure->prt_name_en }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="arrival_port">Arrival
                                                            Port</label>
                                                        <select class="form-control" id="arrival_port"
                                                            name="arrival_port">
                                                            <option value="">Select Arrival Port</option>
                                                            @foreach ($availability as $item)
                                                            <option
                                                                value="{{ $item->trip->arrival->prt_name_en }}">
                                                                {{ $item->trip->arrival->prt_name_en }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fast_boat">Fast Boat</label>
                                                        <select class="form-control" id="fast_boat" name="fast_boat">
                                                            <option value="">Select Fast Boat</option>
                                                            @foreach ($availability as $item)
                                                            <option value="{{ $item->trip->fastboat->fb_name }}">
                                                                {{ $item->trip->fastboat->fb_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="time_dept">Time Dept</label>
                                                        <select class="form-control" id="time_dept" name="time_dept">
                                                            <option value="">Select Time Dept</option>
                                                            @foreach ($availability as $item)
                                                            @php
                                                            // Tentukan waktu keberangkatan dari availability atau trip
                                                            $deptTime = $item->fba_dept_time
                                                            ? $item->fba_dept_time
                                                            : $item->trip->fbt_dept_time;
                                                            @endphp
                                                            <option
                                                                value="{{ date('H:i', strtotime($deptTime)) }}">
                                                                {{ date('H:i', strtotime($deptTime)) }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="p-4 border-top custom-border-top-color">
                                            <!-- Hasil Pencarian -->
                                            <div id="search-results" style="display: none;">
                                                <div class="table-responsive">
                                                    <h5 class="card-title"></h5>
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
                                                                <label class="form-label" for="adult_publish">Adult
                                                                    Publish (IDR)</label>
                                                                <input value="" class="form-control"
                                                                    id="adult_publish" name="adult_publish" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="child_publish">Child
                                                                    Publish (IDR)</label>
                                                                <input value="" class="form-control"
                                                                    id="child_publish" name="child_publish" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="total_end">End Total
                                                                    (IDR)</label>
                                                                <input value="" class="form-control"
                                                                    id="total_end" name="total_end"
                                                                    style="background-color:lightgray" disabled>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="currency_end">End Total
                                                                    Currency (IDR)</label>
                                                                <input value="" class="form-control"
                                                                    id="currency_end" name="currency_end"
                                                                    style="background-color:lightgray" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-border-color">
                                <div class="text-body" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Trip Return</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                            <div class="form-check form-switch"
                                                style="display: flex; align-items: center;justify-content: center;">
                                                <input class="form-check-input"
                                                    style="width: 3rem; height: 1.75rem; border-radius: 1rem;"
                                                    type="checkbox" id="switch" name="switch" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <!-- Form Pencarian -->
                                        <form id="searchForm">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="trip_return_date">Trip
                                                            Date</label>
                                                        <input type="date" class="form-control"
                                                            id="trip_return_date" name="trip_return_date" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="departure_return_port">Departure Port</label>
                                                        <select class="form-control" id="departure_return_port"
                                                            name="departure_return_port" disabled>
                                                            <option value="">Select Departure Port</option>
                                                            @foreach ($availability as $item)
                                                            <option
                                                                value="{{ $item->trip->departure->prt_name_en }}">
                                                                {{ $item->trip->departure->prt_name_en }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="arrival_return_port">Arrival
                                                            Port</label>
                                                        <select class="form-control" id="arrival_return_port"
                                                            name="arrival_return_port" disabled>
                                                            <option value="">Select Arrival Port</option>
                                                            @foreach ($availability as $item)
                                                            <option
                                                                value="{{ $item->trip->arrival->prt_name_en }}">
                                                                {{ $item->trip->arrival->prt_name_en }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fast_boat_return">Fast
                                                            Boat</label>
                                                        <select class="form-control" id="fast_boat_return"
                                                            name="fast_boat_return" disabled>
                                                            <option value="">Select Fast Boat</option>
                                                            @foreach ($availability as $item)
                                                            <option value="{{ $item->trip->fastboat->fb_name }}">
                                                                {{ $item->trip->fastboat->fb_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="time_dept_return">Time
                                                            Dept</label>
                                                        <select class="form-control" id="time_dept_return"
                                                            name="time_dept_return" disabled>
                                                            <option value="">Select Time Dept</option>
                                                            @foreach ($availability as $item)
                                                            @php
                                                            // Tentukan waktu keberangkatan dari availability atau trip
                                                            $deptTime = $item->fba_dept_time
                                                            ? $item->fba_dept_time
                                                            : $item->trip->fbt_dept_time;
                                                            @endphp
                                                            <option
                                                                value="{{ date('H:i', strtotime($deptTime)) }}">
                                                                {{ date('H:i', strtotime($deptTime)) }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <!-- Hasil Pencarian -->
                                        <div id="search-results-return" style="display: none;">
                                            <div class="table-responsive">
                                                <h5 class="card-title-return"></h5>
                                                <table id="booking-data-table-return"
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
                                                            <label class="form-label" for="adult_return_publish">Adult
                                                                Publish (IDR)</label>
                                                            <input value="" class="form-control"
                                                                id="adult_return_publish" name="adult_return_publish"
                                                                disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="child_return_publish">Child
                                                                Publish (IDR)</label>
                                                            <input value="" class="form-control"
                                                                id="child_return_publish" name="child_return_publish"
                                                                disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="total_return_end">End Total
                                                                (IDR)</label>
                                                            <input value="" class="form-control"
                                                                id="total_return_end" name="total_return_end"
                                                                style="background-color:lightgray" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="currency_return_end">End
                                                                Total Currency (IDR)</label>
                                                            <input value="" class="form-control"
                                                                id="currency_return_end" name="currency_return_end"
                                                                style="background-color:lightgray" disabled>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-border-color">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true"
                                    aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Other</h5>
                                                <p class="text-muted text-truncate mb-0">If there are certain
                                                    conditions, please add notes</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <!-- Form Pencarian -->
                                        <form id="note">
                                            <div class="row">
                                                <div class="mb-3">
                                                    <label class="form-label" for="trip_return_date">Note</label>
                                                    <textarea class="form-control" name="" id=""></textarea>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Button --}}
                <div class="row mb-4">
                    <div class="col text-end">
                        <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                class="bx bx-x me-1"></i> Cancel</button>
                        <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Save </button>
                    </div> <!-- end col -->
                </div> <!-- end row-->
            </form>
            <!-- end row -->

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    @include('admin.components.footer')
</div>
@endsection

@section('script')
<script>
    // Tom select 
    new TomSelect("#nationality");
    new TomSelect("#currency");

    // Pencarian trip
    $(document).ready(function() {
        // Fungsi untuk mengecek apakah semua field sudah diisi
        function resetSearchResults() {
            $('#booking-data-table tbody').empty(); // Hapus data di tabel
            $('.card-title').empty(); // Hapus judul kartu
            $('#adult_publish').val(''); // Reset harga dewasa
            $('#child_publish').val(''); // Reset harga anak
            $('#total_end').val(''); // Reset total harga
            $('#currency_end').val(''); // Reset format mata uang
            $('#search-results').hide(); // Sembunyikan hasil pencarian
        }

        // Fungsi untuk mengecek apakah semua field sudah diisi
        function checkFormComplete() {
            let tripDate = $('#trip_date').val();
            let departurePort = $('#departure_port').val();
            let arrivalPort = $('#arrival_port').val();
            let fastBoat = $('#fast_boat').val();
            let timeDept = $('#time_dept').val();

            console.log('Form inputs:', {
                tripDate: tripDate,
                departurePort: departurePort,
                arrivalPort: arrivalPort,
                fastBoat: fastBoat,
                timeDept: timeDept
            });

            // Cek apakah semua field sudah diisi
            if (tripDate && departurePort && arrivalPort && fastBoat && timeDept) {
                performSearch(tripDate, departurePort, arrivalPort, fastBoat, timeDept);
            } else {
                $('#search-results').hide(); // Sembunyikan hasil pencarian jika form tidak lengkap
            }
        }


        // Trigger ketika input field berubah (pencarian ulang)
        $('#trip_date, #departure_port, #arrival_port, #fast_boat, #time_dept').on('change', function() {
            var type = $(this).attr('name');
            if (type === 'trip_date') {
                $('#departure_port').empty().append('<option value="">Select Departure Port</option>');
                $('#arrival_port').empty().append('<option value="">Select Arrival Port</option>');
                $('#fast_boat').empty().append('<option value="">Select Fast Boat</option>');
                $('#time_dept').empty().append('<option value="">Select Time Dept</option>');
                console.log(type);
            } else if (type === 'departure_port') {
                $('#arrival_port').empty().append('<option value="">Select Arrival Port</option>');
                $('#fast_boat').empty().append('<option value="">Select Fast Boat</option>');
                $('#time_dept').empty().append('<option value="">Select Time Dept</option>');
                console.log(type);
            } else if (type === 'arrival_port') {
                $('#fast_boat').empty().append('<option value="">Select Fast Boat</option>');
                $('#time_dept').empty().append('<option value="">Select Time Dept</option>');
            } else if (type === 'fast_boat') {
                $('#time_dept').empty().append('<option value="">Select Time Dept</option>');
            }
            $('#booking-data-table tbody').empty(); // Hapus data di tabel
            $('.card-title').empty(); // Hapus judul kartu
            $('#adult_publish').val(''); // Reset harga dewasa
            $('#child_publish').val(''); // Reset harga anak
            $('#total_end').val(''); // Reset total harga
            $('#currency_end').val(''); // Reset format mata uang
            $('#search-results').hide(); // Sembunyikan hasil pencarian
            checkFormComplete(); // Cek dan lakukan pencarian jika semua field terisi
        });

        // Fungsi untuk melakukan pencarian
        function performSearch(tripDate, departurePort, arrivalPort, fastBoat, timeDept) {
            // Hitung total berdasarkan jumlah pelanggan
            let adultCount = $('#adult_count').val() || 1; // Default 1 dewasa
            let childCount = $('#child_count').val() || 0;

            // Fungsi untuk memperbarui label sesuai mata uang yang dipilih
            function updateCurrencyLabel() {
                let selectedOption = $('#currency').find('option:selected');
                let currencyCode = selectedOption.data('code') || 'IDR'; // Ambil kode mata uang atau default ke IDR
                // Ubah teks label
                $('label[for="currency_end"]').text('End Total Currency (' + currencyCode + ')');
            }

            $.ajax({
                url: "{{ route('data.search') }}",
                method: 'GET',
                data: {
                    trip_date: tripDate,
                    departure_port: departurePort,
                    arrival_port: arrivalPort,
                    fast_boat: fastBoat,
                    time_dept: timeDept,
                    adult_count: adultCount,
                    child_count: childCount
                },
                success: function(response) {
                    if (response.html) {
                        // Hasil baru ditemukan, tampilkan tabel hasil
                        $('#booking-data-table tbody').html(response.html);
                        $('.card-title').html(response.card_title);

                        let adultPublishPrice = parseInt(response.adult_publish.replace(/\./g, '')) || 0;
                        let childPublishPrice = parseInt(response.child_publish.replace(/\./g, '')) || 0;
                        let discountPerPerson = parseInt(response.discount.replace(/\./g, '')) || 0;

                        let adultCount = parseInt($('#adult_count').val()) || 1;
                        let childCount = parseInt($('#child_count').val()) || 0;

                        let totalDiscount = (adultCount + childCount) * discountPerPerson;
                        let totalPriceBeforeDiscount = (adultPublishPrice * adultCount) + (childPublishPrice * childCount);
                        let totalPriceAfterDiscount = totalPriceBeforeDiscount - totalDiscount;

                        if (totalPriceAfterDiscount < 0) {
                            totalPriceAfterDiscount = 0;
                        }

                        $('#adult_publish').val(response.adult_publish);
                        $('#child_publish').val(response.child_publish);
                        $('#total_end').val(totalPriceAfterDiscount.toLocaleString('id-ID'));

                        // Ambil nilai rate dan kode mata uang yang dipilih dari dropdown
                        let selectedOption = $('#currency').find('option:selected');
                        let rate = parseFloat(selectedOption.data('rate')) || 1; // Default ke 1 jika rate tidak ditemukan
                        let currencyCode = selectedOption.data('code') || 'IDR'; // Default ke IDR jika kode tidak ditemukan

                        // Lakukan konversi jika rate tidak sama dengan 1
                        let convertedTotal = totalPriceAfterDiscount / rate;

                        // Format hasil konversi
                        let formattedTotal = convertedTotal.toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        // Set nilai pada kolom currency_end
                        $('#currency_end').val(currencyCode + ' ' + formattedTotal);

                        // Perbarui label sesuai dengan mata uang yang dipilih
                        updateCurrencyLabel();

                        // Tampilkan hasil pencarian baru
                        $('#search-results').show();
                    } else {
                        // Jika tidak ada hasil atau stok tidak mencukupi
                        resetSearchResults(); // Sembunyikan hasil pencarian
                        resetDropdowns(); // Bersihkan dropdown
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Ketika jumlah orang dewasa atau anak-anak diubah, sembunyikan hasil pencarian dan minta pencarian ulang
        $('#adult_count, #child_count').on('change', function() {
            $('#trip_date').val('');
            resetSearchResults(); // Sembunyikan hasil pencarian
            resetDropdowns(); // Bersihkan dropdown
        });

        // Fungsi untuk mendapatkan jumlah customer
        function getCustomerCount() {
            var adultCount = $('#adult_count').val() || 1;
            var childCount = $('#child_count').val() || 0;
            return {
                adult_count: adultCount,
                child_count: childCount
            };
        }

        // Bersihkan dropdown ketika input berubah
        function resetDropdowns() {
            $('#departure_port').empty().append('<option value="">Select Departure Port</option>');
            $('#arrival_port').empty().append('<option value="">Select Arrival Port</option>');
            $('#fast_boat').empty().append('<option value="">Select Fast Boat</option>');
            $('#time_dept').empty().append('<option value="">Select Time Dept</option>');
        }


        // Ketika tanggal dipilih, reset dropdown dan ambil data untuk Departure Port
        $('#trip_date').change(function() {
            resetSearchResults(); // Sembunyikan hasil pencarian saat tanggal berubah
            resetDropdowns(); // Reset dropdown

            var tripDate = $(this).val();
            var customerCount = getCustomerCount(); // Ambil jumlah customer

            $.ajax({
                url: '/getFilteredData',
                method: 'GET',
                data: $.extend({
                    trip_date: tripDate
                }, customerCount),
                success: function(response) {
                    $('#departure_port').empty().append(
                        '<option value="">Select Departure Port</option>');
                    $.each(response.departure_ports, function(index, port) {
                        $('#departure_port').append('<option value="' + port +
                            '">' + port + '</option>');
                    });
                }
            });
        });

        // Ketika Departure Port dipilih, reset dropdown dan ambil data untuk Arrival Port
        $('#departure_port').change(function() {
            $('#arrival_port').empty().append(
                '<option value="">Select Arrival Port</option>'); // Reset Arrival Port
            $('#fast_boat').empty().append(
                '<option value="">Select Fast Boat</option>'); // Reset Fast Boat
            $('#time_dept').empty().append(
                '<option value="">Select Time Dept</option>'); // Reset Time Dept

            var tripDate = $('#trip_date').val();
            var departurePort = $(this).val();
            var customerCount = getCustomerCount(); // Ambil jumlah customer

            $.ajax({
                url: '/getFilteredData',
                method: 'GET',
                data: $.extend({
                    trip_date: tripDate,
                    departure_port: departurePort
                }, customerCount),
                success: function(response) {
                    // Isi dropdown Arrival Port berdasarkan response
                    $('#arrival_port').empty().append(
                        '<option value="">Select Arrival Port</option>');
                    $.each(response.arrival_ports, function(index, port) {
                        $('#arrival_port').append('<option value="' + port + '">' +
                            port + '</option>');
                    });
                }
            });
        });

        // Ketika Arrival Port dipilih, reset dropdown dan ambil data untuk Fast Boat
        $('#arrival_port').change(function() {
            $('#fast_boat').empty().append(
                '<option value="">Select Fast Boat</option>'); // Reset Fast Boat
            $('#time_dept').empty().append(
                '<option value="">Select Time Dept</option>'); // Reset Time Dept

            var tripDate = $('#trip_date').val();
            var departurePort = $('#departure_port').val();
            var arrivalPort = $(this).val();
            var customerCount = getCustomerCount(); // Ambil jumlah customer

            $.ajax({
                url: '/getFilteredData',
                method: 'GET',
                data: $.extend({
                    trip_date: tripDate,
                    departure_port: departurePort,
                    arrival_port: arrivalPort
                }, customerCount),
                success: function(response) {
                    // Isi dropdown Fast Boat berdasarkan response
                    $('#fast_boat').empty().append(
                        '<option value="">Select Fast Boat</option>');
                    $.each(response.fast_boats, function(index, boat) {
                        $('#fast_boat').append('<option value="' + boat + '">' +
                            boat + '</option>');
                    });
                }
            });
        });

        // Ketika Fast Boat dipilih, ambil data untuk Time Dept
        $('#fast_boat').change(function() {
            $('#time_dept').empty().append(
                '<option value="">Select Time Dept</option>'); // Reset Time Dept

            var tripDate = $('#trip_date').val();
            var departurePort = $('#departure_port').val();
            var arrivalPort = $('#arrival_port').val();
            var fastBoat = $(this).val();
            var customerCount = getCustomerCount(); // Ambil jumlah customer

            $.ajax({
                url: '/getFilteredData',
                method: 'GET',
                data: $.extend({
                    trip_date: tripDate,
                    departure_port: departurePort,
                    arrival_port: arrivalPort,
                    fast_boat: fastBoat
                }, customerCount),
                success: function(response) {
                    // Isi dropdown Time Dept berdasarkan response
                    $('#time_dept').empty().append(
                        '<option value="">Select Time Dept</option>');
                    $.each(response.time_depts, function(index, time) {
                        $('#time_dept').append('<option value="' + time + '">' +
                            time + '</option>');
                    });
                }
            });
        });
    });
</script>

{{-- Return --}}
<script>
    $(document).ready(function() {
        // Mengaktifkan atau menonaktifkan field ketika switch diubah
        $('#switch').on('change', function() {
            var inputs = $(
                '#fast_boat_return, #time_dept_return, #trip_return_date, #departure_return_port, #arrival_return_port'
            );
            if ($(this).is(':checked')) {
                inputs.removeAttr('disabled');
            } else {
                inputs.attr('disabled', 'disabled');
            }
        });

        // Fungsi untuk mengecek apakah semua field telah diisi
        function checkFormComplete() {
            let tripDateReturn = $('#trip_return_date').val();
            let departurePortReturn = $('#departure_return_port').val();
            let arrivalPortReturn = $('#arrival_return_port').val();
            let fastBoatReturn = $('#fast_boat_return').val();
            let timeDeptReturn = $('#time_dept_return').val();

            if (tripDateReturn && departurePortReturn && arrivalPortReturn && fastBoatReturn &&
                timeDeptReturn) {
                performSearch(tripDateReturn, departurePortReturn, arrivalPortReturn, fastBoatReturn,
                    timeDeptReturn);
            }
        }

        // Fungsi untuk reset hasil pencarian
        function resetSearchResultsReturn() {
            $('#booking-data-table-return tbody').empty();
            $('.card-title-return').empty();
            $('#adult_return_publish').val('');
            $('#child_return_publish').val('');
            $('#total_return_end').val('');
            $('#currency_return_end').val('');
            $('#search-results-return').hide();
        }

        // Trigger ketika input diubah
        $('#trip_return_date, #departure_return_port, #arrival_return_port, #fast_boat_return, #time_dept_return')
            .on('change', function() {
                var type = $(this).attr('name');
                if (type === 'trip_return_date') {
                    $('#departure_return_port').empty().append('<option value="">Select Departure Port</option>');
                    $('#arrival_return_port').empty().append('<option value="">Select Arrival Port</option>');
                    $('#fast_boat_return').empty().append('<option value="">Select Fast Boat</option>');
                    $('#time_dept_return').empty().append('<option value="">Select Time Dept</option>');
                    console.log(type);
                } else if (type === 'departure_return_port') {
                    $('#arrival_return_port').empty().append('<option value="">Select Arrival Port</option>');
                    $('#fast_boat_return').empty().append('<option value="">Select Fast Boat</option>');
                    $('#time_dept_return').empty().append('<option value="">Select Time Dept</option>');
                    console.log(type);
                } else if (type === 'arrival_return_port') {
                    $('#fast_boat_return').empty().append('<option value="">Select Fast Boat</option>');
                    $('#time_dept_return').empty().append('<option value="">Select Time Dept</option>');
                } else if (type === 'fast_boat_return') {
                    $('#time_dept_return').empty().append('<option value="">Select Time Dept</option>');
                }
                $('#booking-data-table-return tbody').empty(); // Hapus data di tabel
                $('.card-title-return').empty(); // Hapus judul kartu
                $('#adult_return_publish').val(''); // Reset harga dewasa
                $('#child_return_publish').val(''); // Reset harga anak
                $('#total_return_end').val(''); // Reset total harga
                $('#currency_return_end').val(''); // Reset format mata uang
                $('#search-results-return').hide(); // Sembunyikan hasil pencarian

                checkFormComplete(); // Cek dan lakukan pencarian jika semua field terisi
            });

        // Fungsi untuk melakukan pencarian
        function performSearch(tripDateReturn, departurePortReturn, arrivalPortReturn, fastBoatReturn, timeDeptReturn) {
            $.ajax({
                url: "{{ route('data.searchReturn') }}",
                method: 'GET',
                data: {
                    trip_return_date: tripDateReturn,
                    departure_return_port: departurePortReturn,
                    arrival_return_port: arrivalPortReturn,
                    fast_boat_return: fastBoatReturn,
                    time_dept_return: timeDeptReturn
                },
                success: function(response) {
                    if (response.htmlReturn) {
                        $('#booking-data-table-return tbody').html(response.htmlReturn);
                        $('.card-title-return').html(response.card_return_title);

                        let adultPublishPriceReturn = parseInt(response.adult_return_publish.replace(/\./g, '')) || 0;
                        let childPublishPriceReturn = parseInt(response.child_return_publish.replace(/\./g, '')) || 0;
                        let discountPerPersonReturn = parseInt(response.discount_return.replace(/\./g, '')) || 0;

                        let adultCount = parseInt($('#adult_count').val()) || 1;
                        let childCount = parseInt($('#child_count').val()) || 0;

                        let totalDiscountReturn = (adultCount + childCount) * discountPerPersonReturn;
                        let totalPriceBeforeDiscountReturn = (adultPublishPriceReturn * adultCount) + (childPublishPriceReturn * childCount);
                        let totalPriceAfterDiscountReturn = totalPriceBeforeDiscountReturn - totalDiscountReturn;
                        totalPriceAfterDiscountReturn = Math.max(totalPriceAfterDiscountReturn, 0);

                        let selectedOption = $('#currency').find('option:selected');
                        let rate = parseFloat(selectedOption.data('rate')) || 1;
                        let currencyCode = selectedOption.data('code') || 'IDR';

                        let convertedTotalReturn = totalPriceAfterDiscountReturn / rate;
                        let formattedTotalReturn = convertedTotalReturn.toLocaleString('id-ID', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        $('#adult_return_publish').val(response.adult_return_publish);
                        $('#child_return_publish').val(response.child_return_publish);
                        $('#total_return_end').val(totalPriceAfterDiscountReturn.toLocaleString('id-ID'));
                        $('#currency_return_end').val(currencyCode + ' ' + formattedTotalReturn);
                        $('label[for="currency_return_end"]').text('End Total Currency (' + currencyCode + ')');
                        $('#search-results-return').show();
                    } else {
                        resetSearchResultsReturn(); // Sembunyikan hasil pencarian
                        resetDropdownsReturn(); // Bersihkan dropdown
                    }
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }

        // Ketika jumlah dewasa atau anak diubah, sembunyikan hasil pencarian dan reset dropdowns
        $('#adult_count, #child_count').on('change', function() {
            resetSearchResultsReturn(); // Sembunyikan hasil pencarian
            resetDropdownsReturn(); // Reset dropdowns
            resetTripReturnDate(); // Reset input tanggal
        });

        // Fungsi untuk mengambil jumlah customer
        function getCustomerCountReturn() {
            var adultCount = $('#adult_count').val() || 1;
            var childCount = $('#child_count').val() || 0;
            return {
                adult_count: adultCount,
                child_count: childCount
            };
        }

        // Fungsi untuk reset dropdown
        function resetDropdownsReturn() {
            $('#departure_return_port').empty().append('<option value="">Select Departure Port</option>');
            $('#arrival_return_port').empty().append('<option value="">Select Arrival Port</option>');
            $('#fast_boat_return').empty().append('<option value="">Select Fast Boat</option>');
            $('#time_dept_return').empty().append('<option value="">Select Time Dept</option>');
        }

        // Fungsi untuk reset input tanggal return
        function resetTripReturnDate() {
            $('#trip_return_date').val(''); // Reset nilai input tanggal return
        }


        // Ketika trip_return_date diubah, reset dropdown dan lakukan pencarian data baru
        $('#trip_return_date').change(function() {
            resetSearchResultsReturn();
            resetDropdownsReturn();
            let tripDateReturn = $(this).val();
            let customerCount = getCustomerCountReturn();

            $.ajax({
                url: '/getFilteredDataReturn',
                method: 'GET',
                data: $.extend({
                    trip_return_date: tripDateReturn
                }, customerCount),
                success: function(response) {
                    $('#departure_return_port').empty().append(
                        '<option value="">Select Departure Port</option>');
                    $.each(response.departure_return_ports, function(index, port) {
                        $('#departure_return_port').append('<option value="' +
                            port + '">' + port + '</option>');
                    });
                }
            });
        });

        // Ketika departure_return_port diubah, reset dan ambil data baru untuk arrival_port
        $('#departure_return_port').change(function() {
            $('#arrival_return_port').empty().append('<option value="">Select Arrival Port</option>');
            $('#fast_boat_return, #time_dept_return').empty().append(
                '<option value="">Select</option>');

            let tripDateReturn = $('#trip_return_date').val();
            let departurePortReturn = $(this).val();
            let customerCount = getCustomerCountReturn();

            $.ajax({
                url: '/getFilteredDataReturn',
                method: 'GET',
                data: $.extend({
                    trip_return_date: tripDateReturn,
                    departure_return_port: departurePortReturn
                }, customerCount),
                success: function(response) {
                    $('#arrival_return_port').empty().append(
                        '<option value="">Select Arrival Port</option>');
                    $.each(response.arrival_return_ports, function(index, port) {
                        $('#arrival_return_port').append('<option value="' + port +
                            '">' + port + '</option>');
                    });
                }
            });
        });

        // Ketika arrival_return_port diubah, reset dan ambil data baru untuk fast_boat
        $('#arrival_return_port').change(function() {
            $('#fast_boat_return, #time_dept_return').empty().append(
                '<option value="">Select</option>');

            let tripDateReturn = $('#trip_return_date').val();
            let departurePortReturn = $('#departure_return_port').val();
            let arrivalPortReturn = $(this).val();
            let customerCount = getCustomerCountReturn();

            $.ajax({
                url: '/getFilteredDataReturn',
                method: 'GET',
                data: $.extend({
                    trip_return_date: tripDateReturn,
                    departure_return_port: departurePortReturn,
                    arrival_return_port: arrivalPortReturn
                }, customerCount),
                success: function(response) {
                    $('#fast_boat_return').empty().append(
                        '<option value="">Select Fast Boat</option>');
                    $.each(response.fast_boats_return, function(index, boat) {
                        $('#fast_boat_return').append('<option value="' + boat +
                            '">' + boat + '</option>');
                    });
                }
            });
        });

        // Ketika fast_boat_return diubah, ambil data baru untuk time_dept
        $('#fast_boat_return').change(function() {
            $('#time_dept_return').empty().append('<option value="">Select Time Dept</option>');

            let tripDateReturn = $('#trip_return_date').val();
            let departurePortReturn = $('#departure_return_port').val();
            let arrivalPortReturn = $('#arrival_return_port').val();
            let fastBoatReturn = $(this).val();
            let customerCount = getCustomerCountReturn();

            $.ajax({
                url: '/getFilteredDataReturn',
                method: 'GET',
                data: $.extend({
                    trip_return_date: tripDateReturn,
                    departure_return_port: departurePortReturn,
                    arrival_return_port: arrivalPortReturn,
                    fast_boat_return: fastBoatReturn
                }, customerCount),
                success: function(response) {
                    $('#time_dept_return').empty().append(
                        '<option value="">Select Time Dept</option>');
                    $.each(response.time_depts_return, function(index, time) {
                        $('#time_dept_return').append('<option value="' + time +
                            '">' + time + '</option>');
                    });
                }
            });
        });
    });
</script>

<script>
    $('#payment_method').on('change', function() {
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
</script>

<script>
    $(document).ready(function() {
        function updateInfo() {
            // Clear all current info
            $('#adult_info, #child_info, #infant_info').empty();

            // Get values of each count
            var adultCount = parseInt($('#adult_count').val()) || 1;
            var childCount = parseInt($('#child_count').val()) || 0;
            var infantCount = parseInt($('#infant_count').val()) || 0;

            // Create Adult Info
            for (var i = 1; i <= adultCount; i++) {
                $('#adult_info').append(`
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="adult_name_${i}">Adult ${i} Name</label>
                            <input id="adult_name_${i}" name="adult_name_${i}" type="text" placeholder="Enter Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="adult_age_${i}">Adult ${i} Age</label>
                            <select id="adult_age_${i}" name="adult_age_${i}" class="form-control">
                                <option value="">Select Age</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="adult_gender_${i}">Adult ${i} Gender</label>
                            <select id="adult_gender_${i}" name="adult_gender_${i}" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="adult_nationality_${i}">Adult ${i} Nationality</label>
                            <select id="adult_nationality_${i}" name="adult_nationality_${i}" class="nationality-select">
                                <option value="">Select Nationality</option>
                                @foreach ($nationality as $item)
                                <option value="{{ $item->nas_id }}">{{ $item->nas_country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            `);
            }

            // Create Child Info
            for (var i = 1; i <= childCount; i++) {
                $('#child_info').append(`
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="child_name_${i}">Child ${i} Name</label>
                            <input id="child_name_${i}" name="child_name_${i}" type="text" placeholder="Enter Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="child_age_${i}">Child ${i} Age</label>
                            <select id="child_age_${i}" name="child_age_${i}" class="form-control">
                                <option value="">Select Age</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="child_gender_${i}">Child ${i} Gender</label>
                            <select id="child_gender_${i}" name="child_gender_${i}" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="child_nationality_${i}">Child ${i} Nationality</label>
                            <select id="child_nationality_${i}" name="child_nationality_${i}" class="nationality-select">
                                <option value="">Select Nationality</option>
                                @foreach ($nationality as $item)
                                <option value="{{ $item->nas_id }}">{{ $item->nas_country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            `);
            }

            // Create Infant Info
            for (var i = 1; i <= infantCount; i++) {
                $('#infant_info').append(`
                <div class="row">
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="infant_name_${i}">Infant ${i} Name</label>
                            <input id="infant_name_${i}" name="infant_name_${i}" type="text" placeholder="Enter Name" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="infant_age_${i}">Infant ${i} Age</label>
                            <select id="infant_age_${i}" name="infant_age_${i}" class="form-control">
                                <option value="">Select Age</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="infant_gender_${i}">Infant ${i} Gender</label>
                            <select id="infant_gender_${i}" name="infant_gender_${i}" class="form-control">
                                <option value="">Select Gender</option>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="mb-3">
                            <label class="form-label" for="infant_nationality_${i}">Infant ${i} Nationality</label>
                            <select id="infant_nationality_${i}" name="infant_nationality_${i}" class="nationality-select">
                                <option value="">Select Nationality</option>
                                @foreach ($nationality as $item)
                                <option value="{{ $item->nas_id }}">{{ $item->nas_country }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            `);
            }
            // Initialize Tom Select on all newly created select elements with class 'nationality-select'
            $('.nationality-select').each(function() {
                new TomSelect(this);
            });
        }

        // Call updateInfo whenever values change
        $('#adult_count, #child_count, #infant_count').on('input', updateInfo);

        // Initialize info on page load
        updateInfo();
    });
</script>
@endsection