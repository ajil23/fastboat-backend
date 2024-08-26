@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('port.update', $portEdit->prt_id)}}" method="post" enctype="multipart/form-data">
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
                                                <h5 class="font-size-16 mb-1">Port Info</h5>
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
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_name_en">Name (en)</label>
                                                    <input id="prt_name_en" name="prt_name_en" placeholder="Enter Port Name in English" type="text" class="form-control" value="{{$portEdit->prt_name_en}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_name_idn">Name (idn)</label>
                                                    <input id="prt_name_idn" name="prt_name_idn" placeholder="Enter Port Name in Bahasa" type="text" class="form-control" value="{{$portEdit->prt_name_idn}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_code">Code</label>
                                                    <input id="prt_code" name="prt_code" placeholder="Enter Port Code" type="text" class="form-control" value="{{$portEdit->prt_code}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_map">Maps</label>
                                                    <input id="prt_map" name="prt_map" placeholder="Longitude,Latitude" type="text" class="form-control" value="{{$portEdit->prt_map}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_island">Island</label>
                                                    <select class="form-control" name="prt_island" aria-label="Default select example" required>
                                                        <option value="{{$portEdit->prt_island}}" selected>{{$portEdit->island->isd_name}}</option>
                                                        @foreach ($island as $masterisland)
                                                        <option value="{{ $masterisland->isd_id }}">
                                                            {{ $masterisland->isd_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="prt_address">Address</label>
                                            <textarea id="prt_address" name="prt_address" cols="30" rows="5" class="form-control" required>{{$portEdit->prt_address}}</textarea>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_content_en">Content (en)</label>
                                                    <textarea class="form-control" id="prt-content-en" name="prt_content_en" placeholder="Enter Content" rows="4">{{$portEdit->prt_content_en}}</textarea>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_content_idn">Content (idn)</label>
                                                    <textarea class="form-control" id="prt-content-idn" name="prt_content_idn" placeholder="Enter Description" rows="4">{{$portEdit->prt_content_idn}}</textarea>
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
                                                <h5 class="font-size-16 mb-1">Port Image</h5>
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
                                                    <center>
                                                        <img class="rounded me-2" src="{{ asset('storage/'.$portEdit->prt_image1) }}" id="previewImage1" data-holder-rendered="true" style="height: 100px; width:100px;">
                                                    </center>
                                                    <br>
                                                    <label class="form-label" for="prt_image1">Image 1</label>
                                                    <input id="prt_image1" name="prt_image1" type="file" accept="image/*" class="form-control" value="{{$portEdit->prt_image1}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <center>
                                                        <img class="rounded me-2" src="{{ asset('storage/'.$portEdit->prt_image2) }}" id="previewImage2" data-holder-rendered="true" style="height: 100px; width:100px;">
                                                    </center>
                                                    <br>
                                                    <label class="form-label" for="prt_image2">Image 2</label>
                                                    <input id="prt_image2" name="prt_image2" type="file" accept="image/*" class="form-control" value="{{$portEdit->prt_image2}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <center>
                                                        <img class="rounded me-2" src="{{ asset('storage/'.$portEdit->prt_image3) }}" id="previewImage3" data-holder-rendered="true" style="height: 100px; width:100px;">
                                                    </center>
                                                    <br>
                                                    <label class="form-label" for="prt_image3">Image 3</label>
                                                    <input id="prt_image3" name="prt_image3" type="file" accept="image/*" class="form-control" value="{{$portEdit->prt_image3}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <center>
                                                        <img class="rounded me-2" src="{{ asset('storage/'.$portEdit->prt_image4) }}" id="previewImage4" data-holder-rendered="true" style="height: 100px; width:100px;">
                                                    </center>
                                                    <br>
                                                    <label class="form-label" for="prt_image4">Image 4</label>
                                                    <input id="prt_image4" name="prt_image4" type="file" accept="image/*" class="form-control" value="{{$portEdit->prt_image4}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <center>
                                                        <img class="rounded me-2" src="{{ asset('storage/'.$portEdit->prt_image5) }}" id="previewImage5" data-holder-rendered="true" style="height: 100px; width:100px;">
                                                    </center>
                                                    <br>
                                                    <label class="form-label" for="prt_image5">Image 5</label>
                                                    <input id="prt_image5" name="prt_image5" type="file" accept="image/*" class="form-control" value="{{$portEdit->prt_image5}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <center>
                                                        <img class="rounded me-2" src="{{ asset('storage/'.$portEdit->prt_image6) }}" id="previewImage6" data-holder-rendered="true" style="height: 100px; width:100px;">
                                                    </center>
                                                    <br>
                                                    <label class="form-label" for="prt_image6">Image 6</label>
                                                    <input id="prt_image6" name="prt_image6" type="file" accept="image/*" class="form-control" value="{{$portEdit->prt_image6}}">
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
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar">
                                                    <div class="avatar-title rounded-circle bg-dark-subtle  text-dark">
                                                        <h5 class="text-dark font-size-17 mb-0">03</h5>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Meta Data</h5>
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
                                        <div class="mb-3">
                                            <label class="form-label" for="prt_keyword">Keywords</label>
                                            <input id="prt_keyword" name="prt_keyword" placeholder="Enter Keyword" type="text" class="form-control" value="{{$portEdit->prt_keyword}}">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_slug_en">Slug (en)</label>
                                                    <input id="prt_slug_en" name="prt_slug_en" placeholder="Enter Port Slug" type="text" class="form-control" value="{{$portEdit->prt_slug_en}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_slug_idn">Slug (idn)</label>
                                                    <input id="prt_slug_idn" name="prt_slug_idn" placeholder="Enter Port Slug" type="text" class="form-control" value="{{$portEdit->prt_slug_idn}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_description_en">Description (en)</label>
                                                    <textarea class="form-control" id="prt_description_en" name="prt_description_en" placeholder="Enter Description" rows="4">{{$portEdit->prt_description_en}}</textarea>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="prt_description_idn">Description (idn)</label>
                                                    <textarea class="form-control" id="prt_description_idn" name="prt_description_idn" placeholder="Enter Description" rows="4">{{$portEdit->prt_description_idn}}</textarea>
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


@section('script')
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#prt-content-idn').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#prt-content-en').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']]
            ]
        });
    });
</script>

<!-- Preview Image -->
<script>
    const fileInput1 = document.querySelector('input[name="prt_image1"]');
    const previewImage1 = document.getElementById('previewImage1');

    fileInput1.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage1.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
    const fileInput2 = document.querySelector('input[name="prt_image2"]');
    const previewImage2 = document.getElementById('previewImage2');

    fileInput2.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage2.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
    const fileInput3 = document.querySelector('input[name="prt_image3"]');
    const previewImage3 = document.getElementById('previewImage3');

    fileInput3.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage3.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
    const fileInput4 = document.querySelector('input[name="prt_image4"]');
    const previewImage4 = document.getElementById('previewImage4');

    fileInput4.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage4.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
    const fileInput5 = document.querySelector('input[name="prt_image5"]');
    const previewImage5 = document.getElementById('previewImage5');

    fileInput5.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage5.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
    const fileInput6 = document.querySelector('input[name="prt_image6"]');
    const previewImage6 = document.getElementById('previewImage6');

    fileInput6.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                previewImage6.src = event.target.result;
            };

            reader.readAsDataURL(file);
        }
    });
</script>

{{-- javascript syntax to autfill slug --}}
<script>
    document.getElementById('prt_name_en').addEventListener('input', function() {
        const prt_name_en = this.value;
        const prt_slug_en = prt_name_en.toLowerCase().replace(/ /g, '-');
        document.getElementById('prt_slug_en').value = prt_slug_en;
    });

    document.getElementById('prt_name_idn').addEventListener('input', function() {
        const prt_name_idn = this.value;
        const prt_slug_idn = prt_name_idn.toLowerCase().replace(/ /g, '-');
        document.getElementById('prt_slug_idn').value = prt_slug_idn;
    });
</script>
@endsection