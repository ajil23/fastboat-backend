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
                                <h5 class="card-title">Shuttle Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <a href="{{route('shuttle.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i>Shuttle</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="search" name="search" class="form-control rounded bg-light border-0" placeholder="Search by Trip" id="search-input"><i class="bx bx-search search-icon"></i>
                                            </div>
                                        </div>
                                        <tr>
                                            <th></th>
                                            <th scope="col" class="ps-4" style="width: 50px;">
                                                <div class="form-check font-size-16">
                                                    <input type="checkbox" class="checkedbox" id="contacusercheck1">
                                                </div>
                                            </th>
                                            <th>No</th>
                                            <th>From => To</th>
                                            <th><center>Time Range (WITA)</center></th>
                                            <th><center>Detail</center></th>
                                            <th><center>Action</center></th>
                                          </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shuttleData as $item)
                                        <tr>
                                            <th colspan="7" class="table-light"><center>{{$item->area->sa_name}} ({{$item->trip->first()->schedule->company->cpn_name}})</center></th>
                                        </tr>
                                        <tr>
                                            <td class="table-light"><center>{{$item->trip->schedule->sch_name}}</center></td>
                                            <th scope="row" class="ps-4">
                                                <div class="form-check font-size-16">
                                                    <input type="checkbox" class="checkedbox" id="contacusercheck1">
                                                </div>
                                            </th>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->trip->departure->prt_name_en ." " . "(" . date('H:i', strtotime($item->trip->fbt_dept_time)) . ")" . " " ."=>" . " " . $item->trip->arrival->prt_name_en . " " ."(" .  date('H:i', strtotime($item->trip->fbt_arrival_time)) .")"}}</td>
                                            <td><center>{{date('H:i', strtotime($item->s_start)). "-" . date('H:i', strtotime($item->s_end));}}</center></td>
                                            <td><center>{{$item->s_meeting_point}}</center></td>
                                            <td>
                                                <center>
                                                    <div class="dropstart">
                                                        <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                            <i class="mdi mdi-dots-horizontal"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="#">Edit</a>
                                                            <a class="dropdown-item" data-confirm-delete="true" href="#">Delete</a>
                                                        </div>
                                                    </div>
                                                </center>
                                            </td>
                                        </tr>
                                        <tr>
                                        @endforeach
                                    </tbody>
                                    {{-- {{$shuttleData->links('pagination::bootstrap-5')}} --}}
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
@endsection

@section('script')
<script>
    $(function(e){
        $("#contacusercheck1").click(function(){
            $('.checkedbox').prop('checked',$(this).prop('checked'));
        })
    });
</script>
@endsection