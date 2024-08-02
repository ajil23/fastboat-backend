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
                                        <a href="{{ route('shuttle.add') }}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i> Shuttle</a>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <form id="itemsForm" action="{{ route('shuttle.multiple') }}" method="POST">
                                    @csrf
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <!-- Button to trigger modal -->
                                                    <div>
                                                        <button type="button" class="btn btn-dark w-100" id="updateButton" data-bs-toggle="modal" data-bs-target="#updateModal" disabled><i class="mdi mdi-pencil"></i> Update</button>
                                                    </div>
                                                </th>
                                                <th scope="col" class="ps-4" style="width: 50px;">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="checkedbox" id="sa_id" onclick="toggleSelectAll(this)">
                                                    </div>
                                                </th>
                                                <th>No</th>
                                                <th>From -> To</th>
                                                <th><center>Time Range (WITA)</center></th>
                                                <th><center>Detail</center></th>
                                                <th><center>Action</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shuttleData as $key => $item)
                                                @if ($key == 0 || $shuttleData[$key]->area->sa_name != $shuttleData[$key - 1]->area->sa_name || $shuttleData[$key]->trip->schedule->company->cpn_name != $shuttleData[$key - 1]->trip->schedule->company->cpn_name)
                                                    <tr>
                                                        <th colspan="7" class="table-light"><center>{{ $item->area->sa_name }} ({{ $item->trip->schedule->company->cpn_name }})</center></th>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td class="table-light"><center>{{ $item->trip->schedule->sch_name }}</center></td>
                                                    <th scope="row" class="ps-4">
                                                        <div class="form-check font-size-16">
                                                            <input type="checkbox" class="checkedbox" name="selected_ids[]" value="{{ $item->s_id }}" onclick="updateButtonState()">
                                                        </div>
                                                    </th>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->trip->departure->prt_name_en . " (" . date('H:i', strtotime($item->trip->fbt_dept_time)) . ") => " . $item->trip->arrival->prt_name_en . " (" . date('H:i', strtotime($item->trip->fbt_arrival_time)) . ")" }}</td>
                                                    <td><center>{{ date('H:i', strtotime($item->s_start)) . "-" . date('H:i', strtotime($item->s_end)) }}</center></td>
                                                    <td><center>{{ $item->s_meeting_point }}</center></td>
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Modal -->
                                    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateModalLabel">Update Items</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="s_start">Start</label>
                                                        <input type="time" class="form-control" id="s_start" name="s_start">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="s_end">End</label>
                                                        <input type="time" class="form-control" id="s_end" name="s_end">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="s_meeting_point">Meeting Point</label>
                                                        <input type="text" class="form-control" id="s_meeting_point" name="s_meeting_point">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Update </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
function toggleSelectAll(checkbox) {
    const isChecked = checkbox.checked;
    document.querySelectorAll('input[name="selected_ids[]"]').forEach(function (cb) {
        cb.checked = isChecked;
    });
    updateButtonState();
}

function updateButtonState() {
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    const updateButton = document.getElementById('updateButton');
    let isAnyChecked = false;

    checkboxes.forEach(function (checkbox) {
        if (checkbox.checked) {
            isAnyChecked = true;
        }
    });

    updateButton.disabled = !isAnyChecked;
}
</script>
@endsection
