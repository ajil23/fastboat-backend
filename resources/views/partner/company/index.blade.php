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
                                            <th>Whatsapp</th>
                                            <th>Address</th>
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
                                            <td>
                                                {{$item->cpn_email}}
                                                {{-- @if ($item->cpn_email_status)
                                                    @if ($item->cpn_email_status == 'enable')
                                                    <span class="badge bg-success">Enable</span>
                                                    @else
                                                    <span class="badge bg-danger">Disable</span>
                                                    @endif
                                                @endif --}}
                                                <a href="{{route('company.emailStatus', $item->cpn_id)}}" class="badge rounded-pill bg-{{$item->cpn_email_status ? 'success' : 'danger'}}"><i class="mdi mdi-{{$item->cpn_email_status ? 'check-decagram' : 'alert-decagram'}}"></i>
                                                    {{$item->cpn_email_status ? 'Enable' : 'Disable'}}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="https://wa.me/{{$item->cpn_whatsapp}}" class="badge bg-success-subtle text-success font-size-12">
                                                    <i class="mdi mdi-whatsapp"></i>Chat
                                                </a>
                                            </td>
                                            <td>
                                                {{$item->cpn_address}}
                                            </td>
                                            <td>
                                                <a href="{{route('company.status', $item->cpn_id)}}" class="badge rounded-pill bg-{{$item->cpn_status ? 'success' : 'danger'}}" ><i class="mdi mdi-{{$item->cpn_status ? 'check-decagram' : 'alert-decagram'}}"></i>
                                                    {{$item->cpn_status ? 'Enable' : 'Disable'}}
                                                </a>
                                            </td>
                                            <td>
                                                @if ($item->cpn_type)
                                                    @if($item->cpn_type === 'fast_boat')
                                                        <span class="badge bg-primary-subtle text-primary font-size-12"><i class="mdi mdi-sail-boat"></i>Fast Boat</span>
                                                    @elseif($item->cpn_type === 'car_transfer')
                                                        <span class="badge bg-primary-subtle text-primary font-size-12"><i class="mdi mdi-car"></i>Car Transfer</span>
                                                    @elseif($item->cpn_type === 'yacht')
                                                        <span class="badge bg-primary-subtle text-primary font-size-12"><i class="mdi mdi-ship-wheel"></i>Yacht</span>
                                                    @elseif($item->cpn_type === 'tour')
                                                        <span class="badge bg-primary-subtle text-primary font-size-12"><i class="mdi mdi-hiking"></i>Tour</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="javascript:void(0)" id="showDetail" data-url="{{route('company.show', $item->cpn_id)}}">View</a>
                                                        <a class="dropdown-item" href="{{route('company.edit', $item->cpn_id)}}">Edit</a>
                                                        <a class="dropdown-item" onclick="return confirm('Are you sure?')" href="{{route('company.delete', $item->cpn_id)}}" >Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    {{$company->links('pagination::bootstrap-5')}}
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

    <!-- Scrollable modal for view detail-->
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="viewDetailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailModalTitle">Company Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name : </strong><span id="company-name"></span></p>
                <p><strong>Email : </strong><span id="company-email"></span></p>
                <p><strong>Phone : </strong><span id="company-phone"></span></p>
                <p><strong>Whatsapp : </strong><span id="company-whatsapp"></span></p>
                <p><strong>Address : </strong><span id="company-address"></span></p>
                <p><strong>Website : </strong><span id="company-website"></span></p>
                <p><strong>Type : </strong><span id="company-type"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @include('admin.components.footer')
</div>
@endsection

@section('script')
{{-- javascript to get data from database & view in modal --}}
<script type="text/javascript">
    $(document).ready(function(){
        $('body').on('click', '#showDetail', function(){
            var detailURL = $(this).data('url');
            $.get(detailURL, function(data){
                $('#viewDetailModal').modal('show');
                    $('#company-name').text(data.cpn_name);
                    $('#company-email').text(data.cpn_email);
                    $('#company-phone').text(data.cpn_phone);
                    $('#company-whatsapp').text(data.cpn_whatsapp);
                    $('#company-address').text(data.cpn_address);
                    $('#company-website').text(data.cpn_website);
                    $('#company-type').text(data.cpn_type);
            })
        })
    });
</script>
@endsection