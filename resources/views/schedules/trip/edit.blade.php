@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('trip.update', $tripEdit->fbt_id)}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div id="addproduct-accordion" class="custom-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1"> Fastboat Trip Info</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="mb-3">
                                            <label class="form-label" for="fbt_name">Name*</label>
                                            <input id="fbt_name" name="fbt_name" placeholder="Enter Trip Name" type="text" class="form-control" value="{{$tripEdit->fbt_name}}">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_route">Fastboat Route*</label>
                                                    <select aria-label="Default select example" name="fbt_route" class="form-control" required>
                                                        <option value="{{$tripEdit->fbt_route}}" selected>{{$tripEdit->route->rt_dept_island}} to {{$tripEdit->route->rt_arrival_island}}</option>
                                                        @foreach ($route as $item)
                                                        <option value="{{$item->rt_id}}">
                                                            {{$item->rt_dept_island}} to {{$item->rt_arrival_island}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label for="fbt_fastboat" class="form-label">Fast Boat</label>
                                                    <select data-trigger name="fbt_fastboat" id="fbt_fastboat" required>
                                                        <option value="{{$tripEdit->fbt_fastboat}}" selected>{{$tripEdit->fastboat->fb_name}}</option>
                                                        @foreach ($fastboat as $item)
                                                            <option value="{{$item->fb_id}}">{{$item->fb_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_schedule">Fastboat Schedule*</label>
                                                    <select id="fbt_schedule" name="fbt_schedule" aria-label="Default select example" class="form-control" required>
                                                        <option value="{{$tripEdit->fbt_schedule}}" selected>{{$tripEdit->schedule->sch_name}}</option>
                                                        @foreach ($schedule as $item)
                                                        <option value="{{$item->sch_id}}">
                                                            {{$item->sch_name}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_dept_port">Departured Port*</label>
                                                    <select id="fbt_dept_port" name="fbt_dept_port" aria-label="Default select example" class="form-control" required>
                                                        <option value="{{$tripEdit->fbt_dept_port}}" selected>{{$tripEdit->departure->prt_name_en}}</option>
                                                        @foreach ($departure as $item)
                                                        <option value="{{$item->prt_id}}">
                                                            {{$item->prt_name_en}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_dept_time">Departured Time*</label>
                                                    <input id="fbt_dept_time" name="fbt_dept_time" placeholder="Enter Time" type="time" class="form-control" value="{{$tripEdit->fbt_dept_time}}" required>
                                                    </input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_arrival_port">Arrival Port*</label>
                                                    <select class="form-control" id="fbt_arrival_port" name="fbt_arrival_port" aria-label="Defaut select example" required>
                                                        <option value="{{$tripEdit->fbt_arrival_port}}" selected>{{$tripEdit->arrival->prt_name_en}}</option>
                                                        @foreach ($arrival as $item)
                                                        <option value="{{$item->prt_id}}">
                                                            {{$item->prt_name_en}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_arrival_time">Arrival Time*</label>
                                                    <input class="form-control" id="fbt_arrival_time" name="fbt_arrival_time" placeholder="Enter Content" type="time" value="{{$tripEdit->fbt_arrival_time}}" required></input>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_time_limit">Time Limit Booked*</label>
                                                    <input class="form-control" id="fbt_time_limit" name="fbt_time_limit" placeholder="Enter Time" type="time" value="{{$tripEdit->fbt_time_limit}}" required></input>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_time_gap">Time Gap*</label>
                                                    <input class="form-control" id="fbt_time_gap" name="fbt_time_gap" placeholder="Enter Content" type="time" value="{{$tripEdit->fbt_time_gap}}" required></input>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="row">
                                            
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_shuttle_type">Shuttle Type</label>
                                                    <input class="form-control" id="fbt_shuttle_type" name="fbt_shuttle_type" placeholder="Enter Shuttle Type" type="text" value="{{$tripEdit->fbt_shuttle_type}}" required></input>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_shuttle_option">Shuttle Option</label>
                                                    <input class="form-control" id="fbt_shuttle_option" name="fbt_shuttle_option" placeholder="Enter Shuttle Option" type="text" value="{{$tripEdit->fbt_shuttle_option}}" required></input>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_shuttle_type">Shuttle Type</label>
                                                    <br>
                                                    <input class="form-check-input" type="radio" id="null" name="fbt_shuttle_type" value="null" @if($tripEdit->fbt_shuttle_type == 'null') checked @endif>
                                                    <label for="null">Null</label>
                                                    <input class="form-check-input" type="radio" id="private" name="fbt_shuttle_type" value="private" @if($tripEdit->fbt_shuttle_type == 'private') checked @endif>
                                                    <label for="private">Private</label>
                                                    <input class="form-check-input" type="radio" id="Sharing" name="fbt_shuttle_type" value="sharing" @if($tripEdit->fbt_shuttle_type == 'sharing') checked @endif>
                                                    <label for="Sharing">Sharing</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_shuttle_option">Shuttle Option</label>
                                                    <br>
                                                    <input class="form-check-input" type="radio" id="pickup" name="fbt_shuttle_option" value="pickup" @if($tripEdit->fbt_shuttle_option == 'pickup') checked @endif>
                                                    <label for="pickup">Pick up</label>
                                                    <input class="form-check-input" type="radio" id="dropoff" name="fbt_shuttle_option" value="dropoff" @if($tripEdit->fbt_shuttle_option == 'dropoff') checked @endif>
                                                    <label for="dropoff">Drop Off</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_info_en">Info (en)</label>
                                                    <textarea class="form-control" id="fbt_info_en" name="fbt_info_en" placeholder="Enter Content" rows="6">{{$tripEdit->fbt_info_en}}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbt_info_idn">Info (idn)</label>
                                                    <textarea class="form-control" id="fbt_info_idn" name="fbt_info_idn" placeholder="Enter Content" rows="4">{{$tripEdit->fbt_info_idn}}</textarea>
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
@section('script')

<script>
    const tipeRadioButtons = document.querySelectorAll('input[name="fbt_shuttle_type"]');
    const opsiRadioButtons = document.querySelectorAll('input[name="fbt_shuttle_option"]');
  
    tipeRadioButtons.forEach(button => {
      button.addEventListener('change', () => {
        const selectedValue = button.value;
        opsiRadioButtons.forEach(opsiButton => {
          if (selectedValue === 'null') {
            opsiButton.disabled = true;
          } else {
            opsiButton.disabled = false;
          }
        });
      });
    });
</script>

{{-- tom select --}}
<script>
    new TomSelect("#fbt_fastboat",{
	    sortField: {
		field: "text",
		direction: "asc"
	    }
    });
</script>
@endsection