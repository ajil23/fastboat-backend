@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('shuttle.update', $shuttleData->s_id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div id="addproduct-accordion" class="custom-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1"> Shuttle Info</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_trip">Trip*</label>
                                                    <select aria-label="Default select example" name="s_trip" class="form-control" required>
                                                        <option value="{{$shuttleData->s_trip}}" selected>{{$shuttleData->trip->fbt_name}}</option>
                                                        @foreach ($trip as $item)
                                                        <option value="{{$item->fbt_id}}">
                                                            {{$item->fbt_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_area">Shuttle Area*</label>
                                                    <select id="s_area" name="s_area" aria-label="Default select example" class="form-control" required>
                                                        <option value="{{$shuttleData->s_area}}" selected>{{$shuttleData->area->sa_name}}</option>
                                                        @foreach ($area as $item)
                                                        <option value="{{$item->sa_id}}">
                                                            {{$item->sa_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_meeting_point">Meeting Point*</label>
                                                    <input id="s_meeting_point" name="s_meeting_point" placeholder="Enter Meeting Point Place" type="text" class="form-control" value="{{$shuttleData->s_meeting_point}}" required></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_start">Start Waiting*</label>
                                                    <input id="s_start" name="s_start" placeholder="Enter Time" type="time" class="form-control" value="{{$shuttleData->s_start}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_end">End Waiting*</label>
                                                    <input id="s_end" name="s_end" placeholder="Enter Time" type="time" class="form-control" value="{{$shuttleData->s_end}}" required>
                                                    </input>
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