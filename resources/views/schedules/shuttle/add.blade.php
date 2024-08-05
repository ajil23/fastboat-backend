@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @csrf
            <form action="{{route('shuttle.search')}}" method="post" enctype="multipart/form-data">
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
                                <div class="collapse show" data-bs-parent="#addproduct-accordion">
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
                                                        @foreach ($trip as $item)
                                                        <option value="{{$item->fbt_id}}"> {{$item->departure->prt_name_en }} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_meeting_point">Arrival Port</label>
                                                    <select data-trigger id="prt_name_arriv" name="prt_name_arriv">
                                                        <option value="">Select Port</option>
                                                        @foreach ($trip as $item)
                                                        <option value="{{$item->fbt_id}}"> {{$item->arrival->prt_name_en}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="s_start">Option</label>
                                                    <select data-trigger id="prt_option" name="prt_option">
                                                        <option value="">Select Port</option>
                                                        @foreach ($trip as $item)
                                                        <option value="{{$item->fbt_id}}"> {{$item->fbt_shuttle_option}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="myCard" class="row mt-2">
                @foreach ($trip as $item)
                <div class="col-xl-4 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="formCheck1">
                                <label class="form-check-label" for="formCheck1">
                                    {{$item->schedule->company->cpn_name}}
                                </label>
                            </div>
                            <div class="mt-3 pt-1">
                                <p> From : {{$item->departure->prt_name_en}}, {{$item->departure->island->isd_name}} ({{date('H:i', strtotime($item->fbt_dept_time))}}) </p>
                                <p>To : {{$item->arrival->prt_name_en}}, {{$item->departure->island->isd_name}} ({{date('H:i', strtotime($item->fbt_arrival_time))}})</p>
                            </div>
                        </div>
                    </div>
                    <!-- end card -->
                </div>
                <!-- end col -->
                @endforeach
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
                                            <th>
                                                <center>No</center>
                                            </th>
                                            <th>
                                                <center>Area</center>
                                            </th>
                                            <th>
                                                <center>Start</center>
                                            </th>
                                            <th>
                                                <center>End</center>
                                            </th>
                                            <th>
                                                <center>Meeting Point</center>
                                            </th>
                                            <th>
                                                <center>Note</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="ps-4">
                                                <div class="form-check font-size-16">
                                                    <input type="checkbox" class="checkedbox" name="#">
                                                </div>
                                            </th>
                                            <td>
                                                <center>1</center>
                                            </td>
                                            <td>
                                                <center>Canggu</center>
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
                                                    <div class="form-check form-switch" style="display: flex; align-items: center;justify-content: center;">
                                                        <input class="form-check-input" style="width: 3rem; height: 1.75rem; border-radius: 1rem;" type="checkbox" id="switch" />
                                                    </div>
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    <input id="s_meeting_point" name="s_meeting_point" placeholder="Note/Meeting Point Location" type="text" class="form-control"></input>
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
    new TomSelect("#prt_option");
</script>

<script>
    // Sembunyikan card dengan ID "myCard"
    document.getElementById('myCard').style.display = 'none';
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
      // Ambil elemen checkbox dan input teks
      const switchElement = document.getElementById('switch');
      const textInput = document.getElementById('s_meeting_point');

      // Fungsi untuk memperbarui status input berdasarkan switch
      function updateInputStatus() {
          textInput.disabled = !switchElement.checked;
      }

      // Tambahkan event listener untuk switch
      switchElement.addEventListener('change', updateInputStatus);

      // Inisialisasi status input pada saat halaman dimuat
      updateInputStatus();
  });
</script>
@endsection