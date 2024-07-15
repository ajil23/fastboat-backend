@extends('admin.admin_master') 
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center mb-2">
                                <h5 class="card-title">Island Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                    <a href="{{route('island.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i> Add New Island</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th  style="width: 90px;">Image</th>
                                            <th>Name</th>
                                            <th>Code</th>
                                            <th>Map</th>
                                            <th>Slug ENG</th>
                                            <th>Slug IDN</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($island as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                                                        <img src="{{ asset('storage/'.$item->isd_image1) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$item->isd_name}}</td>
                                            <td>{{$item->isd_code}}</td>
                                            <td>
                                                    <a href="https://www.google.com/maps/search/?api=1&query= + {{$item->isd_map}}" target="_blank" >
                                                        <b>Lihat</b>
                                                    </td>
                                            <td>{{$item->isd_slug_en}}</td>
                                            <td>{{$item->isd_slug_idn}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">View</a>
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="#">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
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

    @include('admin.components.footer')
</div>