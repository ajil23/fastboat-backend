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
                                                        <b>See</b>
                                                    </td>
                                            <td>{{$item->isd_slug_en}}</td>
                                            <td>{{$item->isd_slug_idn}}</span></td>
                                            <td>
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="javascript:void(0)" id="showDetail" data-url="{{route('island.show', $item->isd_id)}}">View</a>
                                                        <a class="dropdown-item" href="{{route('island.edit', $item->isd_id)}}">Edit</a>
                                                        <a class="dropdown-item" onclick="return confirm('Are you sure?')" href="{{route('island.delete', $item->isd_id)}}" >Delete</a>
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

    
    <!-- Scrollable modal -->
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog"
    aria-labelledby="viewDetailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDetailModalTitle">Island Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name : </strong><span id="island-name"></span></p>
                <p><strong>Code : </strong><span id="island-code"></span></p>
                <p><strong>Map : </strong><span id="island-map"></span></p>
                <p><strong>Keywords : </strong><span id="island-keyword"></span></p>
                <p><strong>Slug EN : </strong><span id="island-slugen"></span></p>
                <p><strong>Slug IND : </strong><span id="island-slugind"></span></p>
                <p><strong>Description EN : </strong><span id="island-descriptionen"></span></p>
                <p><strong>Description IND : </strong><span id="island-descriptionidn"></span></p>
                <p><strong>Content EN : </strong><span id="island-contenten"></span></p>
                <p><strong>Content IND : </strong><span id="island-contentidn"></span></p>
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
                    $('#island-name').text(data.isd_name);
                    $('#island-code').text(data.isd_code);
                    $('#island-map').text(data.isd_map);
                    $('#island-keyword').text(data.isd_keyword);
                    $('#island-slugen').text(data.isd_slug_en);
                    $('#island-slugind').text(data.isd_slug_idn);
                    $('#island-descriptionen').text(data.isd_description_en);
                    $('#island-descriptionidn').text(data.isd_description_idn);
                    $('#island-contenten').text(data.isd_content_en);
                    $('#island-contentidn').text(data.isd_content_idn);
            })
        })
    });
</script>
@endsection
