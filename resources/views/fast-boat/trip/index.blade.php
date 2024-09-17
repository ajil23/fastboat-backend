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
                                <h5 class="card-title">Trip Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <a href="{{route('trip.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i>Trip</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th colspan="10" class="p-0">
                                                <div class="d-flex justify-content-between align-items-center p-2">
                                                    <div class="search-box w-100">
                                                        <div class="position-relative">
                                                            <input type="search" name="search" class="form-control rounded bg-light border-0 w-100" placeholder="Search by Name" id="search-input">
                                                            <i class="bx bx-search search-icon" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Recommend</th>
                                            <th>Route</th>
                                            <th>Fast Boat</th>
                                            <th>Schedule</th>
                                            <th>Departured</th>
                                            <th>Arrival</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trip as $item)
                                        <tr id="baris-{{$item->fbt_id}}" class="search">
                                            <td>{{$loop->iteration}}</td>
                                            <td class="search-item"  data-id="{{$item->fbt_id}}">{{$item->fbt_name}}</td>
                                            <td>
                                                <a href="{{route('trip.status', $item->fbt_id)}}" class="badge rounded-pill bg-{{$item->fbt_status ? 'success' : 'danger'}}"><i class="mdi mdi-{{$item->fbt_status ? 'check-decagram' : 'alert-decagram'}}"></i>
                                                    {{$item->fbt_status ? 'Enable' : 'Disable'}}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{route('trip.recommend', $item->fbt_id)}}" class="badge rounded-pill bg-{{$item->fbt_recom ? 'success' : 'danger'}}"><i class="mdi mdi-{{$item->fbt_recom ? 'check-decagram' : 'alert-decagram'}}"></i>
                                                    {{$item->fbt_recom ? 'Enable' : 'Disable'}}
                                                </a>
                                            </td>
                                            <td class="search-item" data-id="{{$item->rt_id}}">{{$item->route->rt_dept_island}} to {{$item->route->rt_arrival_island}}</td>
                                            <td>{{$item->fastboat->fb_name}}</td>
                                            <td>{{$item->schedule->sch_name}}</td>
                                            <td>{{$item->departure->prt_name_en}} at {{date('H:i', strtotime($item->fbt_dept_time));}}</td>
                                            <td>{{$item->arrival->prt_name_en}} at {{date('H:i', strtotime($item->fbt_arrival_time));}}</td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="javascript:void(0)" id="showDetail" data-url="{{route('trip.show', $item->fbt_id)}}">View</a>
                                                        <a class="dropdown-item" href="{{route('trip.edit', $item->fbt_id)}}">Edit</a>
                                                        <a class="dropdown-item" data-confirm-delete="true" href="{{route('trip.delete', $item->fbt_id)}}">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    {{-- {{$trip->links('pagination::bootstrap-5')}} --}}
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

    <!-- Scrollable modal for view detail -->
    <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog" aria-labelledby="viewDetailModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailModalTitle">Fast Boat Trip Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name : </strong><span id="trip-name"></span></p>
                    <p><strong>Status : </strong><span id="trip-status">Enable</span></p>
                    <p><strong>Route : </strong><span id="trip-route"></span></p>
                    <p><strong>Fast Boat : </strong><span id="trip-fastboat"></span></p>
                    <p><strong>Schedule : </strong><span id="trip-schedule"></span></p>
                    <p><strong>Departured : </strong><span id="trip-departure"></span></p>
                    <p><strong>Departured Time : </strong><span id="trip-dept-time"></span></p>
                    <p><strong>Time Limit for Booked : </strong><span id="trip-time-limit"></span></p>
                    <p><strong>Time Gap : </strong><span id="trip-time-gap"></span></p>
                    <p><strong>Arrival : </strong><span id="trip-arrival"></span></p>
                    <p><strong>Arrival Time : </strong><span id="trip-arrival-time"></span></p>
                    <p><strong>Info (EN) : </strong><span id="trip-info-en"></span></p>
                    <p><strong>Info (IDN) : </strong><span id="trip-info-idn"></span></p>
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
<!-- search box -->
<script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var search = $(this).val();
            var lowerCaseText = search.toLowerCase();
            var list = $('.search-item');
            // console.log(list);
            $('.search').show()
            list.each(function() {
                var item = $(this).text();
                var id = $(this).data('id');
                if (item.toLowerCase().includes(lowerCaseText) === false) {
                    $('#baris-' + id).hide()
                }
            })
        })
    })
</script>
{{-- javascript to get data from database & view in modal --}}
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '#showDetail', function() {
            var detailURL = $(this).data('url');
            $.get(detailURL, function(data) {
                $('#viewDetailModal').modal('show');
                $('#trip-name').text(data.fbt_name);
                $('#trip-status').text(data.fbt_status);
                if (data.fbt_status === 1) {
                    $('#trip-status').text('enable');
                } else {
                    $('#trip-status').text('disable');
                }
                $('#trip-route').text(data.route.rt_dept_island);
                $('#trip-fastboat').text(data.fastboat.fb_name);
                $('#trip-schedule').text(data.schedule.sch_name);
                $('#trip-departure').text(data.departure.prt_name_en);
                $('#trip-dept-time').text(data.fbt_dept_time);
                $('#trip-time-limit').text(data.fbt_time_limit);
                $('#trip-time-gap').text(data.fbt_time_gap);
                $('#trip-arrival').text(data.arrival.prt_name_en);
                $('#trip-arrival-time').text(data.fbt_arrival_time);
                $('#trip-info-en').text(data.fbt_info_en);
                $('#trip-info-idn').text(data.fbt_info_idn);
            })
        })
    });
</script>
@endsection