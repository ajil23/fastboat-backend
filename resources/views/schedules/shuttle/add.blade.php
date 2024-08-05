@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('shuttle.store')}}" method="post" enctype="multipart/form-data">
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
                                                    <select aria-label="Default select example" name="s_trip" class="form-control" required>
                                                        <option selected>Select Trip</option>
                                                        @foreach ($trip as $item)
                                                        <option value="{{$item->fbt_id}}">
                                                            {{$item->fbt_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_area">Departure Port</label>
                                                    <select id="s_area" name="s_area" aria-label="Default select example" class="form-control" required>
                                                        <option selected>Select Shuttle</option>
                                                        @foreach ($area as $item)
                                                        <option value="{{$item->sa_id}}">
                                                            {{$item->sa_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_meeting_point">Arrival Port</label>
                                                    <input id="s_meeting_point" name="s_meeting_point" placeholder="Enter Meeting Point Place" type="text" class="form-control" ></input>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_start">Option</label>
                                                    <input id="s_start" name="s_start" placeholder="Enter Time" type="time" class="form-control" >
                                                    </input>
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
                                        <p>To : Gili Trawangan Port, GIli Trawangan (11:00)</p>
                                    </div>
                                </div>
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
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