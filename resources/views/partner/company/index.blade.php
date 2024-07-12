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
                                <h5 class="card-title">Company Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <a href="{{route('company.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i> Create New Company</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th style="width: 90px;">
                                               Logo
                                            </th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Email Status</th>
                                            <th>Phone</th>
                                            <th>Whatsapp</th>
                                            <th>Address</th>
                                            <th>Website</th>
                                            <th>Status</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($company as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                                                        <img src="{{ asset('storage/'.$item->cpn_logo) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">{{$item->cpn_name}}</td>
                                            <td>{{$item->cpn_email}}</td>
                                            <td><span class="badge bg-primary-subtle text-primary  font-size-12">{{$item->cpn_email_status}}</span></td>
                                            <td>{{$item->cpn_phone}}</td>
                                            <td>{{$item->cpn_whatsapp}}</td>
                                            <td>{{$item->cpn_address}}</td>
                                            <td>{{$item->cpn_website}}</td>
                                            <td><span class="badge bg-success-subtle text-success  font-size-12">{{$item->cpn_status}}</span></td>
                                            <td>{{$item->cpn_type}}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewDetailModal" data-cpn_type = "{{$item->cpn_logo}}" data-cpn_name = "{{$item->cpn_name}}" data-cpn_email = "{{$item->cpn_email}}" data-cpn_phone = "{{$item->cpn_phone}}" data-cpn_whatsapp = "{{$item->cpn_whatsapp}}" data-cpn_address = "{{$item->cpn_address}}" data-cpn_website = "{{$item->cpn_website}}" data-cpn_type = "{{$item->cpn_type}}">View</a>
                                                        <a class="dropdown-item" href="#">Edit</a>
                                                        <a class="dropdown-item" href="{{route('company.delete', $item->cpn_id)}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div style="margin-top: 1%">
                                {{$company->links('pagination::bootstrap-5')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <!-- Scrollable modal -->
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="viewDetailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailModalTitle">Company Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <center>
                    <div class="avatar-lg">
                        <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                            <img src="{{ asset('storage/'.$item->cpn_logo) }}" class="img-fluid" alt="">
                        </div>
                    </div>
                </center>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Name</label>
                    <input type="text" class="form-control" value="{{$item->cpn_name}}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Email</label>
                    <input type="text" class="form-control" value="{{$item->cpn_email}}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Phone</label>
                    <input type="text" class="form-control" value="{{$item->cpn_phone}}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Whatsapp</label>
                    <input type="text" class="form-control" value="{{$item->cpn_whatsapp}}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Address</label>
                    <input type="text" class="form-control" value="{{$item->cpn_address}}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Website</label>
                    <input type="text" class="form-control" value="{{$item->cpn_website}}" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="cpn_name">Company Type</label>
                    <input type="text" class="form-control" value="{{$item->cpn_type}}" disabled>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @include('admin.components.footer')
</div>