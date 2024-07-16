@extends('admin.admin_master') 
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('company.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div id="addproduct-accordion" class="custom-accordion">
                            <div class="card">
                                <a href="#addproduct-productinfo-collapse" class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
    
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded-circle bg-dark-subtle  text-dark">
                                                        <h5 class="text-dark font-size-17 mb-0">01</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Company Info</h5>
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
                                            <div class="mb-3">
                                                <label class="form-label" for="cpn_name">Company Name</label>
                                                <input id="cpn_name" name="cpn_name" placeholder="Enter Company Name" type="text" class="form-control" required>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
    
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_email">Company Email</label>
                                                        <input id="cpn_email" name="cpn_email" placeholder="Enter Company Email" type="email" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
    
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_phone">Company Phone</label>
                                                        <input id="cpn_phone" name="cpn_phone" type="number" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_whatsapp">Company Whatsapp</label>
                                                        <input id="cpn_whatsapp" name="cpn_whatsapp" type="number" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="cpn_email_status" class="form-label">Company Email Status</label>
                                                        <select class="form-control" data-trigger name="cpn_email_status" id="cpn_email_status" required>
                                                            <option value="">Select</option>
                                                            <option value="enable">Enable</option>
                                                            <option selected value="disable">Disable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="cpn_status" class="form-label">Company Status</label>
                                                        <select class="form-control" data-trigger name="cpn_status" id="cpn_status" required>
                                                            <option value="">Select</option>
                                                            <option value="enable">Enable</option>
                                                            <option selected ="disable">Disable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4">
    
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_address">Company Address</label>
                                                        <input id="cpn_address" name="cpn_address" placeholder="Enter Company Address" type="text" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
    
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_website">Company Website</label>
                                                        <input id="cpn_website" name="cpn_website" type="text" class="form-control" placeholder="Enter Company Website (optional)" value="-">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="cpn_type" class="form-label">Company Type</label>
                                                        <select class="form-control" data-trigger name="cpn_type" id="cpn_type" required>
                                                            <option value="">Select</option>
                                                            <option value="fast_boat">Fast Boat</option>
                                                            <option value="car_transfer">Car Transfer</option>
                                                            <option value="yacht">Yacht</option>
                                                            <option value="tour">Tour</option>
                                                        </select>
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
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded-circle bg-dark-subtle  text-dark">
                                                        <h5 class="text-dark font-size-17 mb-0">02</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Company Image</h5>
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
                                        <div class="mb-3">
                                            <label class="form-label" for="cpn_logo">Logo</label>
                                            <input id="cpn_logo" name="cpn_logo" type="file" accept="image/*" class="form-control" required>
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
                        <button onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                        <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Save</button>
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
