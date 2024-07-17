@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
        <form action="{{route('island.update', $islandEdit->isd_id)}}" method="post" enctype="multipart/form-data">
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
                                                <h5 class="font-size-16 mb-1">Island Info</h5>
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
                                                <label class="form-label" for="isd_name">Name</label>
                                                <input id="isd_name" name="isd_name" placeholder="Enter Island Name" type="text" class="form-control" value="{{$islandEdit->isd_name}}" required>
                                            </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_code">Code</label>
                                                    <input id="isd_code" name="isd_code" placeholder="Enter Island Code" type="text" class="form-control" value="{{$islandEdit->isd_code}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_map">Maps</label>
                                                    <input id="isd_map" name="isd_map" placeholder="Longitude,Latitude" type="text" class="form-control" value="{{$islandEdit->isd_map}}" required>
                                                    </input>
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
                                                <h5 class="font-size-16 mb-1">Island Image</h5>
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
                                                    <label class="form-label" for="isd_image1">Image 1</label>
                                                    <input id="isd_image1" name="isd_image1" type="file" accept="image/*" class="form-control" value="{{$islandEdit->isd_image1}}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">

                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_image2">Image 2</label>
                                                    <input id="isd_image2" name="isd_image2" type="file" accept="image/*" class="form-control" value="{{$islandEdit->isd_image2}}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_image3">Image 3</label>
                                                    <input id="isd_image3" name="isd_image3" type="file" accept="image/*" class="form-control" value="{{$islandEdit->isd_image3}}" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">

                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_image4">Image 4</label>
                                                    <input id="isd_image4" name="isd_image4" type="file" accept="image/*" class="form-control" value="{{$islandEdit->isd_image4}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">

                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_image5">Image 5</label>
                                                    <input id="isd_image5" name="isd_image5" type="file" accept="image/*" class="form-control" value="{{$islandEdit->isd_image5}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="idn_image6">Image 6</label>
                                                    <input id="idn_image6" name="idn_image6" type="file" accept="image/*" class="form-control" value="{{$islandEdit->isd_image6}}">
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
                                                <label class="form-label" for="isd_keyword">Keywords</label>
                                                <input id="isd_keyword" name="isd_keyword" placeholder="Enter Keyword" type="text" class="form-control" value="{{$islandEdit->isd_keyword}}">
                                            </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_slug_en">Slug (en)</label>
                                                    <input id="isd_slug_en" name="isd_slug_en" placeholder="Enter Island Slug" type="text" class="form-control" value="{{$islandEdit->isd_slug_en}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_slug_idn">Slug (idn)</label>
                                                    <input id="isd_slug_idn" name="isd_slug_idn" placeholder="Enter Island Slug" type="text" class="form-control" value="{{$islandEdit->isd_slug_idn}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_description_en">Description (en)</label>
                                                    <textarea class="form-control" id="isd_description_en" name="isd_description_en" placeholder="Enter Description" rows="4">{{$islandEdit->isd_description_en}}</textarea>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_description_idn">Description (idn)</label>
                                                    <textarea class="form-control" id="isd_description_idn" name="isd_description_idn" placeholder="Enter Description" rows="4">{{$islandEdit->isd_description_idn}}</textarea>
                                                </div>
                                            </div>
                                        </div> 
                                        
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_content_en">Content (en)</label>
                                                    <textarea class="form-control" id="isd-content-en" name="isd_content_en" placeholder="Enter Content" rows="4">{{$islandEdit->isd_content_en}}</textarea>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="isd_content_idn">Content (idn)</label>
                                                    <textarea class="form-control" id="isd-content-idn" name="isd_content_idn" placeholder="Enter Description" rows="4">{{$islandEdit->isd_content_idn}}</textarea>
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
<script type="importmap">
            {
                "imports": {
                    "ckeditor5": "https://cdn.ckeditor.com/ckeditor5/42.0.1/ckeditor5.js",
                    "ckeditor5/": "https://cdn.ckeditor.com/ckeditor5/42.0.1/"
                }
            }
        </script>

        <script type="module">
            import {
                ClassicEditor,
                Essentials,
                Bold,
                Italic,
                Font,
                Paragraph
            } from 'ckeditor5';

            ClassicEditor
                .create( document.querySelector( '#isd-content-en' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );

            ClassicEditor
                .create( document.querySelector( '#isd-content-idn' ), {
                    plugins: [ Essentials, Bold, Italic, Font, Paragraph ],
                    toolbar: {
                        items: [
                            'undo', 'redo', '|', 'bold', 'italic', '|',
                            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
                        ]
                    }
                } )
                .then( /* ... */ )
                .catch( /* ... */ );
        </script>
@endsection