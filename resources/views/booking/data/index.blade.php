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
                            <a href="{{route('data.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i>Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <a href="#addproduct-img-collapse" class="text-body collbodyd" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-img-collapse">
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
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Order ID</label>
                                    <input type="text" name="company" id="search-company" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Booking ID</label>
                                    <input type="text" name="company" id="search-company" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Contact Name</label>
                                    <input type="text" name="company" id="search-company" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Contact Name</label>
                                    <input type="text" name="company" id="search-company" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Passenger Name</label>
                                    <input type="text" name="company" id="search-company" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <select name="company" id="search-company" class="form-control">
                                        <option value="">~All Company~</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Departure</label>
                                    <select name="departure" id="search-departure" class="form-control">
                                        <option value="">~All Departure Port~</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Arrival</label>
                                    <select name="arrival" id="search-arrival" class="form-control">
                                        <option value="">~All Arrival Port~</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Source</label>
                                    <select name="route" id="search-route" class="form-control">
                                        <option value="">~All Source~</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Trans Status</label>
                                    <select name="route" id="search-route" class="form-control">
                                        <option value="">~All Trans Status~</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Range Trip</label>
                                    <select name="departure" id="search-departure" class="form-control">
                                        <option value="">Trip Date</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input name="daterange" type="text" class="form-control flatpickr-input" id="daterange" placeholder="Input date range" value="">
                                </div>
                            </div>
                            <div class="position-relative text-center pb-3">
                                <button type="submit" class="btn btn-outline-dark"><i class="mdi mdi-magnify"></i>&thinsp;Search</button>
                            </div>
                        </div>
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
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
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
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <div>
                                                    <span class="badge bg-info" data-bs-toggle="modal" data-bs-target="#passengerModal">{{$item->fbo_source}}</span>
                                                    <strong>{{$item->contact->ctc_name}}</strong> ~ {{$item->contact->ctc_email}} ~ {{$item->contact->ctc_phone}} ~ {{$item->created_at}}
                                                </div>
                                                <div>
                                                    <strong>{{$item->fbo_booking_id}}</strong> ~ <strong>{{$item->trip->fastboat->fb_name}}</strong> {{$item->trip->departure->island->isd_name}} <span class="text-danger">({{$item->fbo_trip_date}} {{$item->fbo_departure_time}})</span> => {{$item->trip->arrival->island->isd_name}} ~ <strong>{{$item->fbo_adult + $item->fbo_child}} pax</strong> ({{$item->fbo_adult}} Adult, {{$item->fbo_child}} Child, {{$item->fbo_infant}} Infant)
                                                </div>
                                            </td>
                                            <td>
                                                <center>
                                                    <span class="text-success"><i class="fas fa-check-circle"></i> paid</span><br>
                                                    <a href="#" class="text-primary">balance</a>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <span class="text-warning"><i class="fas fa-sync-alt"></i> Unconfirm</span><br>
                                                    <a href="#" class="text-primary"><i class="fas fa-check-circle"></i> Confirmed</a>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <div class="dropstart">
                                                        <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                            <i class="mdi mdi-dots-horizontal"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="javascript:void(0)" id="showDetail" data-url="#">View</a>
                                                            <a class="dropdown-item" href="#">Edit</a>
                                                            <a class="dropdown-item" onclick="return confirm('Are you sure?')" href="#">Delete</a>
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
    <!-- End Page-content -->

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="passengerModal" tabindex="-1" aria-labelledby="passengerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-title">
                    <h5 class="text-center">
                        <span class="text-danger">FAENEQX</span> (Idola Express)<br>
                        Banjar Nyuh Port (Nusa Penida, 13:15) To Sanur Port (Bali, 13:45)
                    </h5>
                </div>
                <div class="modal-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" id="passengers-tab" data-bs-toggle="tab" href="#passengers">Passengers</a>
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
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Piotr Leszczynski</td>
                                        <td>Male</td>
                                        <td>28</td>
                                        <td>Other</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Valeriia Budashko</td>
                                        <td>Female</td>
                                        <td>29</td>
                                        <td>Other</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <center>
                                    <strong>Passenger Note :</strong>
                                    <p>~</p>
                                    <strong>Meeting Point :</strong>
                                    <p>Ped, Nusa Penida, Klungkung Regency, Bali 80771</p>
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
                                        <td>2</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td>IDR 110.000</td>
                                        <td>IDR 80.000</td>
                                        <td>IDR 0</td>
                                    </tr>
                                    <tr>
                                        <th>Sub Total</th>
                                        <td>IDR 220.000</td>
                                        <td>IDR 0</td>
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
                                            <center>IDR 220.000</center>
                                        </td>
                                        <td>
                                            <center>IDR 0x2pax</center>
                                        </td>
                                        <td>
                                            <center>IDR 0</center>
                                        </td>
                                        <td>
                                            <center>IDR 0</center>
                                        </td>
                                        <td>
                                            <center>IDR 220.000</center>
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
                                            <center>IDR 200.000</center>
                                        </td>
                                        <td><strong>
                                                <center>IDR 20.000</center>
                                            </strong></td>
                                        <td colspan="3">
                                            <center></center>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Balance ID:</td>
                                        <td colspan="4">2JN34I2N3TRDDXB42P32</td>
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
                                    <tbody>
                                        <tr>
                                            <td>
                                                <center>@reservasi</center>
                                            </td>
                                            <td>
                                                <center>Mark as confirm</center>
                                            </td>
                                            <td colspan="2">
                                                <center>19-10-2024 19:40</center>
                                            </td>
                                        </tr>
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
</div>
</div>
@include('admin.components.footer')
</div>
@endsection

@section('script')
@endsection