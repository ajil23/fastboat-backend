@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('data.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div id="addproduct-accordion" class="custom-accordion">
                            <div class="card">
                                <a href="#addproduct-productinfo-collapse" class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Payment</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Name</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Name" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Email</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Email" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Phone</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Phone" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Nationality</label>
                                                    <select id="fb_name" name="fb_name" data-trigger class="form-control" required>
                                                        <option value="">Select Nationality</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Currency</label>
                                                    <select id="fb_name" name="fb_name" data-trigger class="form-control" required>
                                                        <option value="">Select Currency</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Payment Status</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Enter Payment Status" type="text" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Payment Method</label>
                                                    <select id="fb_name" name="fb_name" data-trigger="" class="form-control" required>
                                                        <option value="">Select Payment Method</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4" hidden>
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Transaction ID</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Type Paypal Transaction ID" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4" hidden>
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Transaction ID</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Type Midtrans Transaction ID" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4" hidden>
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Transaction ID</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Type Bank Transaction ID" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4" hidden>
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_name">Transaction ID</label>
                                                    <input id="fb_name" name="fb_name" placeholder="Agen Transaction ID" type="text" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <a href="#addproduct-img-collapse" class="text-body collbodyd" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-img-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Customer</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
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
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Adult</label>
                                                    <input id="fb_image1" name="fb_image1" type="number" class="form-control" value="{{ old('', 1) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Child</label>
                                                    <input id="fb_image1" name="fb_image1" type="number" class="form-control" value="{{ old('', 0) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Infant</label>
                                                    <input id="fb_image1" name="fb_image1" type="number" class="form-control" value="{{ old('', 0) }}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Adult Name</label>
                                                    <input id="fb_image1" name="fb_image1" type="text" placeholder="Enter Name Customer" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Adult Age</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Age</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Adult Gender</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="Woman">Woman</option>
                                                        <option value="Man">Man</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Adult Nationality</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Nationality</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Child Name</label>
                                                    <input id="fb_image1" name="fb_image1" type="text" placeholder="Enter Name Customer" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Child Age</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Age</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Child Gender</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="Woman">Woman</option>
                                                        <option value="Man">Man</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Child Nationality</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Nationality</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Infant Name</label>
                                                    <input id="fb_image1" name="fb_image1" type="text" placeholder="Enter Name Customer" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Infant Age</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Age</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Infant Gender</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Gender</option>
                                                        <option value="Woman">Woman</option>
                                                        <option value="Man">Man</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image1">Infant Nationality</label>
                                                    <select id="fb_image1" name="fb_image1" type="number" class="form-control">
                                                        <option value="">Select Nationality</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <a href="#addproduct-metadata-collapse" class="text-body collbodyd" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-metadata-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Trip Depart</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-metadata-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Trip Date</label>
                                                    <input type="date" class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Departure Port</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Departure Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Arrival Port</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Arrival Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Fast Boat</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Fast Boat</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Time Dept</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Time Dept</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="table-responsive">
                                            <h5 class="card-title">
                                                <center>Booking Data Table<center>
                                            </h5>
                                            <table class="table table-bordered table-centered align-middle table-nowrap mb-0 table-check">
                                                <thead>
                                                    <tr>
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
                                                    <tr>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">Adult Publish (IDR)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">Child Publish (IDR)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">End Total (IDN)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">End Total Currency (IDN)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <a href="#addproduct-return-collapse" class="text-body collbodyd" data-bs-toggle="collapse" aria-haspopup="true" aria-expanded="false" aria-haspopup="true" aria-controls="addproduct-return-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Trip Return</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-return-collapse" class="collapse" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Trip Date</label>
                                                    <input type="date" class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Departure Port</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Departure Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Arrival Port</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Arrival Port</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Fast Boat</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Fast Boat</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Time Dept</label>
                                                    <select data-trigger class="form-control" id="fb_slug_en" name="fb_slug_en" required>
                                                        <option value="">Select Time Dept</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="table-responsive">
                                            <h5 class="card-title">
                                                <center>Booking Data Table<center>
                                            </h5>
                                            <table class="table table-bordered table-centered align-middle table-nowrap mb-0 table-check">
                                                <thead>
                                                    <tr>
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
                                                    <tr>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                        <td>
                                                            <center>#</center>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">Adult Publish (IDR)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">Child Publish (IDR)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">End Total (IDN)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fb_slug_en">End Total Currency (IDN)</label>
                                                        <input value="350.000" class="form-control" id="fb_slug_en" name="fb_slug_en" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Button --}}
                <div class="row mb-4">
                    <div class="col text-end">
                        <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
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