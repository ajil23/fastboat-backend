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
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_name"> Name*</label>
                                                        <input id="cpn_name" name="cpn_name" placeholder="Enter Company Name" type="text" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_website"> Website</label>
                                                        <input id="cpn_website" name="cpn_website" type="text" class="form-control" placeholder="Enter Company Website (optional)">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label for="cpn_type" class="form-label"> Type*</label>
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
                                            <div class="row">
                                                <div class="col-lg-4">
    
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_email"> Email</label>
                                                        <input id="cpn_email" name="cpn_email" placeholder="Enter Company Email" type="email" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
    
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_phone"> Phone*</label>
                                                        <input id="cpn_phone" name="cpn_phone" type="text" class="form-control" placeholder="Enter Company Phone Number" required>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="cpn_whatsapp"> Whatsapp*</label>
                                                        <input id="cpn_whatsapp" name="cpn_whatsapp" type="text" class="form-control" placeholder="Enter Company Whatsapp Number (62)" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="cpn_address"> Address*</label>
                                                <textarea class="form-control" name="cpn_address" id="cpn_address" cols="30" rows="10" required></textarea>
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
                                            <center>
                                                <img class="rounded me-2" src="" id="previewImage" data-holder-rendered="true" style="height: 100px; width:100px;">
                                            </center>
                                            <br>
                                            <label class="form-label" for="cpn_logo">Logo*</label>
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
                        <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
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

@section('script')
<script>
    const fileInput = document.querySelector('input[name="cpn_logo"]');
    const previewImage = document.getElementById('previewImage');

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
</script>
@endsection