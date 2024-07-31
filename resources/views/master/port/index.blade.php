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
                                <h5 class="card-title">Port Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <a href="{{route('port.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i>Port</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th style="width: 90px;">Image</th>
                                            <th>Name ENG</th>
                                            <th>Name IDN</th>
                                            <th>Island</th>
                                            <th>Code</th>
                                            <th>Map</th>
                                            <th>Address</th>
                                            <th>Slug ENG</th>
                                            <th>Slug IDN</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($port as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                <div class="avatar">
                                                    <div class="product-img avatar-title img-thumbnail bg-primary-subtle  border-0">
                                                        <img src="{{ asset('storage/'.$item->prt_image1) }}" class="img-fluid" alt="">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$item->prt_name_en}}</td>
                                            <td>{{$item->prt_name_idn}}</td>
                                            <td>{{$item->island->isd_name}}</td>
                                            <td>{{$item->prt_code}}</td>
                                            <td>
                                            <a href="https://www.google.com/maps/search/?api=1&query= + {{$item->isd_map}}"  class="badge bg-success-subtle text-success  font-size-12" target="blank">
                                            See<i class="mdi mdi-arrow-right"></i></a>
                                            </td>
                                            <td>{{$item->prt_address}}</td>
                                            <td>{{$item->prt_slug_en}}</td>
                                            <td>{{$item->prt_slug_idn}}</td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="javascript:void(0)" id="showDetail" data-url="{{route('port.show', $item->prt_id)}}">View</a>
                                                        <a class="dropdown-item" href="{{route('port.edit', $item->prt_id)}}">Edit</a>
                                                        <a class="dropdown-item" data-confirm-delete="true" href="{{route('port.delete', $item->prt_id)}}" >Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{$port->links('pagination::bootstrap-5')}}
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
                <h5 class="modal-title" id="viewDetailModalTitle">Port Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name ENG : </strong><span id="port-nameen"></span></p>
                <p><strong>Name IDN : </strong><span id="port-nameidn"></span></p>
                <p><strong>Island : </strong><span id="port-island"></span></p>
                <p><strong>Code : </strong><span id="port-code"></span></p>
                <p><strong>Map : </strong><span id="port-map"></span></p>
                <p><strong>Address : </strong><span id="port-address"></span></p>
                <p><strong>Keywords : </strong><span id="port-keyword"></span></p>
                <p><strong>Slug EN : </strong><span id="port-slugen"></span></p>
                <p><strong>Slug IND : </strong><span id="port-slugind"></span></p>
                <p><strong>Description EN : </strong><span id="port-descriptionen"></span></p>
                <p><strong>Description IND : </strong><span id="port-descriptionidn"></span></p>
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
                    $('#port-nameen').text(data.prt_name_en);
                    $('#port-nameidn').text(data.prt_name_idn);
                    $('#port-island').text(data.island.isd_name);
                    $('#port-code').text(data.prt_code);
                    $('#port-map').text(data.prt_map);
                    $('#port-address').text(data.prt_address);
                    $('#port-keyword').text(data.prt_keyword);
                    $('#port-slugen').text(data.prt_slug_en);
                    $('#port-slugind').text(data.prt_slug_idn);
                    $('#port-descriptionen').text(data.prt_description_en);
                    $('#port-descriptionidn').text(data.prt_description_idn);
            })
        })
    });
</script>
@endsection
