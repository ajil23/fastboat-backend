@extends('admin.admin_master') 
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('fastboat.update', $fastboatEdit->fb_id)}}" method="POST" enctype="multipart/form-data">
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
                                                <h5 class="font-size-16 mb-1">Fast Boat Info</h5>
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
                                                <label class="form-label" for="fb_name">Fast Boat Name</label>
                                                <input id="fb_name" name="fb_name" placeholder="Enter Fast Boat Name" type="text" class="form-control" value="{{$fastboatEdit->fb_name}}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="fb_company" class="form-label">Company</label>
                                                    <select class="form-control" data-trigger name="fb_company" id="fb_company">
                                                        <option value="{{$fastboatEdit->fb_company}}" selected>{{$fastboatEdit->partnercompany->cpn_name}}</option>
                                                        @foreach ($company as $item)
                                                            <option value="{{$item->cpn_id}}">{{$item->cpn_name}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fb_status" class="form-label">Status</label>
                                                    <select class="form-control" data-trigger name="fb_status" id="fb_status">
                                                        <option selected="{{$fastboatEdit->fb_status}}">{{$fastboatEdit->fb_status}}</option>
                                                        <option value="enable">Enable</option>
                                                        <option value="disable">Disable</option>
                                                    </select>
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
                                                <h5 class="font-size-16 mb-1">Fast Boat Image</h5>
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
                                                    <label class="form-label" for="fb_image1">Image 1</label>
                                                    <input id="fb_image1" name="fb_image1" type="file" accept="image/*" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">

                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image2">Image 2</label>
                                                    <input id="fb_image2" name="fb_image2" type="file" accept="image/*" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image3">Image 3</label>
                                                    <input id="fb_image3" name="fb_image3" type="file" accept="image/*" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">

                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image4">Image 4</label>
                                                    <input id="fb_image4" name="fb_image4" type="file" accept="image/*" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">

                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image5">Image 5</label>
                                                    <input id="fb_image5" name="fb_image5" type="file" accept="image/*" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_image6">Image 6</label>
                                                    <input id="fb_image6" name="fb_image6" type="file" accept="image/*" class="form-control">
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
                                            <label class="form-label" for="fb_keywords">Keywords</label>
                                            <input id="fb_keywords" name="fb_keywords" placeholder="Enter Fast Boat Keywords" type="text" class="form-control" value="{{$fastboatEdit->fb_keywords}}">
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_en">Slug (en)</label>
                                                    <input type="text" class="form-control" id="fb_slug_en" name="fb_slug_en" placeholder="Enter Slug" rows="4" value="{{$fastboatEdit->fb_slug_en}}"></input>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_slug_idn">Slug (idn)</label>
                                                    <input  type="text"  class="form-control" id="fb_slug_idn" name="fb_slug_idn" placeholder="Enter Slug" rows="4" value="{{$fastboatEdit->fb_slug_idn}}"></input>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_description_en">Description (en)</label>
                                                    <textarea class="form-control" id="fb_description_en" name="fb_description_en" placeholder="Enter Description" rows="4">{{$fastboatEdit->fb_description_en}}</textarea>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_description_idn">Description (idn)</label>
                                                    <textarea class="form-control" id="fb_description_idn" name="fb_description_idn" placeholder="Enter Description" rows="4">{{$fastboatEdit->fb_description_idn}}</textarea>
                                                </div>
                                            </div>
                                        </div> 
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_content_en">Content (en)</label>
                                                    <textarea name="fb_content_en" id="content-en">{{$fastboatEdit->fb_content_en}}</textarea>
                                                </div>

                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fb_content_idn">Content (idn)</label>
                                                    <textarea name="fb_content_idn" id="content-idn">{{$fastboatEdit->fb_content_idn}}</textarea>
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
                        <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Update </button>
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

{{-- ckeditor 5 implementation--}}
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
        .create( document.querySelector( '#content-en' ), {
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
        .create( document.querySelector( '#content-idn' ), {
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