@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('shuttle.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div id="addproduct-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Shuttle Search</h5>
                                                <p class="text-muted text-truncate mb-0">Search the shuttle</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="ms-auto">
                                            <div class="btn-toolbar float-end" role="toolbar">
                                                    <label class="form-label" for="s_trip">Search</label>
                                                    <button type="search" class="btn btn-dark w-100" id="btn-new-event"><i class="bx bx-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_trip">Company</label>
                                                    <select data-trigger name="cpn_name" id="cpn_name" required>
                                                        <option value="">Select Fast Boat Company</option>
                                                        @foreach ($company as $item)
                                                            <option value="{{$item->cpn_id}}">{{$item->cpn_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_area">Departure Port</label>
                                                    <select data-trigger id="prt_name_dept" name="prt_name_dept">
                                                        <option value="">Select Port</option>
                                                        @foreach ($port as $item)
                                                        <option value="{{$item->prt_id}}">
                                                            {{$item->prt_name_en}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_meeting_point">Arrival Port</label>
                                                    <select data-trigger id="prt_name_arriv" name="prt_name_arriv">
                                                        <option value="">Select Port</option>
                                                        @foreach ($port as $item)
                                                        <option value="{{$item->prt_id}}">
                                                            {{$item->prt_name_en}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_start">Option</label>
                                                    <select id="s_area" name="s_area" aria-label="Default select example" class="form-control" required>
                                                        <option selected>Select Option</option>
                                                        <option value="dropoff">Drop Off</option>
                                                        <option value="pickup">Pick Up</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-xl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="formCheck1">
                                        <label class="form-check-label" for="formCheck1">
                                            Karunia Jaya
                                        </label>
                                    </div>
                                    <div class="mt-3 pt-1">
                                        <p> From : Padangbai Harbor, Bali (09:30)</p>
                                        <p>To : Gili Trawangan Port, Gili Trawangan (11:00)</p>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <div class="col-lg-12">
                        <div id="addproduct-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    {{-- add function to select all checkbox --}}
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1"> Shuttle Info</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="ps-4" style="width: 50px;">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="checkedbox" id="sa_id" onclick="toggleSelectAll(this)">
                                                    </div>
                                                </th>
                                                <th><center>No</center></th>
                                                <th><center>Area</center></th>
                                                <th><center>Start</center></th>
                                                <th><center>End</center></th>
                                                <th><center>Meeting Point</center></th>
                                                <th><center>Note</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row" class="ps-4">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="checkedbox" name="#">
                                                    </div>
                                                </th>
                                                <td><center>1</center></td>
                                                <td><center>Canggu</center></td>
                                                <td>
                                                    <center>
                                                        <select class="form-control" data-trigger name="cpn_type" id="cpn_type" required>
                                                            <option value="">Select</option>
                                                            <option value="fast_boat">Fast Boat</option>
                                                            <option value="car_transfer">Car Transfer</option>
                                                            <option value="yacht">Yacht</option>
                                                            <option value="tour">Tour</option>
                                                        </select>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <select class="form-control" data-trigger name="cpn_type" id="cpn_type" required>
                                                            <option value="">Select</option>
                                                            <option value="fast_boat">Fast Boat</option>
                                                            <option value="car_transfer">Car Transfer</option>
                                                            <option value="yacht">Yacht</option>
                                                            <option value="tour">Tour</option>
                                                        </select>
                                                    </center>
                                                </td>
                                                <td>
                                                   <center>
                                                    <input type="checkbox" id="switch9" switch="dark" checked />
                                                    <label for="switch9" data-on-label="Yes" data-off-label="No"></label>
                                                   </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <input id="s_meeting_point" name="s_meeting_point" placeholder="Note/Meeting Point Location" type="text" class="form-control" ></input>
                                                    </center>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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

{{-- tom select --}}
<script>
    new TomSelect("#cpn_name");
    new TomSelect("#prt_name_dept");
    new TomSelect("#prt_name_arriv");
</script>
@endsection