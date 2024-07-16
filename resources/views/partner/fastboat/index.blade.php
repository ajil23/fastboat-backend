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
                                <h5 class="card-title">Fast boat Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <a href="{{route('fastboat.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i> Create New Fast Boat</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th style="width: 90px;">
                                               Picture
                                            </th>
                                            <th  style="width: 210px;">Fast Boat Name</th>
                                            <th>Company Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($fastboat as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                                                        <img src="{{ asset('storage/'.$item->fb_image1) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-semibold">{{$item->fb_name}}</td>
                                            <td>
                                                {{$item->partnercompany->cpn_name}}
                                            </td>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary  font-size-12">{{$item->fb_status}}</span>
                                            </td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="javascript:void(0)" id="showDetail" data-url="{{route('fastboat.show', $item->fb_id)}}">View</a>
                                                        <a class="dropdown-item" href="{{route('fastboat.edit', $item->fb_id)}}">Edit</a>
                                                        <a class="dropdown-item" onclick="return confirm('Are you sure?')" href="{{route('fastboat.delete', $item->fb_id)}}">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    {{$fastboat->links('pagination::bootstrap-5')}}
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

    <!-- Scrollable modal -->
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="viewDetailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailModalTitle">Fast Boat Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name : </strong><span id="fastboat-name"></span></p>
                <p><strong>Keywords : </strong><span id="fastboat-keywords"></span></p>
                <p><strong>Status : </strong><span id="fastboat-status"></span></p>
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
                    $('#fastboat-name').text(data.fb_name);
                    $('#fastboat-keywords').text(data.fb_keywords);
                    $('#fastboat-status').text(data.fb_status);
            })
        })
    });
</script>
@endsection