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
                                        <div class="btn-group me-2 mb-2">
                                            <button type="button" class="btn btn-dark w-100" id="updateButton" data-bs-toggle="modal" data-bs-target="#updateModal" disabled><i class="mdi mdi-pencil"></i></button>
                                            <button type="button" class="btn btn-danger w-100" id="deleteButton" onclick="confirmDelete()" disabled><i class="mdi mdi-delete"></i> </button>
                                            <a href="{{ route('shuttle.add') }}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <form id="itemsForm" action="{{ route('shuttle.multiple') }}" method="POST">
                                    @csrf
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>
                                                    <center>Schedule</center>
                                                </th>
                                                <th>
                                                    <center>From -> To</center>
                                                </th>
                                                <th>
                                                    <center>Time Range (WITA)</center>
                                                </th>
                                                <th>
                                                    <center>Detail</center>
                                                </th>
                                                <th scope="col" class="ps-4" style="width: 50px;">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="form-check-input" id="sa_id" onclick="toggleSelectAll(this)">
                                                    </div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $groupedShuttleData = $shuttleData->groupBy(fn($item) => $item->area->sa_name . '-' . $item->trip->schedule->company->cpn_name);
                                            @endphp

                                            @foreach ($groupedShuttleData as $key => $group)
                                            <tr>
                                                <th colspan="7" class="table-light">
                                                    <center>{{ $group->first()->area->sa_name }} ({{ $group->first()->trip->schedule->company->cpn_name }})</center>
                                                </th>
                                            </tr>

                                            @foreach ($group as $index => $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="table-light">
                                                    <center>{{ $item->trip->schedule->sch_name }}</center>
                                                </td>
                                                <td>{{ $item->trip->departure->prt_name_en . " (" . date('H:i', strtotime($item->trip->fbt_dept_time)) . ") -> " . $item->trip->arrival->prt_name_en . " (" . date('H:i', strtotime($item->trip->fbt_arrival_time)) . ")" }}</td>
                                                <td>
                                                    <center>{{$item->s_start . "-" . $item->s_end }}</center>
                                                </td>
                                                <td>
                                                    @if ($item->s_meeting_point)
                                                        @if($item->s_meeting_point === 'not_set')
                                                        <span><i class="mdi mdi-office-building"></i>- {{$item->trip->fbt_shuttle_option}}</span>
                                                        @else
                                                        <span><i class="mdi mdi-map-marker"></i>{{ $item->s_meeting_point }} - {{$item->trip->fbt_shuttle_option}}</span>
                                                        @endif
                                                    @endif
                                                </td>
                                                <th scope="row" class="ps-4">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="form-check-input" name="selected_ids[]" value="{{ $item->s_id }}" onclick="updateSelectAllState(); updateButtonState()">
                                                    </div>
                                                </th>
                                            </tr>
                                            @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- Modal Updated -->
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
                                                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal" aria-label="Close"><i class="bx bx-x me-1"></i> Cancel</button>
                                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Update </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!-- Form for deleting items -->
                                <form id="deleteForm" action="{{ route('shuttle.deleteMultiple') }}" method="POST" style="display:none;">
                                    @csrf
                                    <input type="hidden" name="selected_ids" id="deleteSelectedIds">
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

<!-- checkebox js -->
<script>
    function toggleSelectAll(checkbox) {
        const isChecked = checkbox.checked;
        document.querySelectorAll('input[name="selected_ids[]"]').forEach(function(cb) {
            cb.checked = isChecked;
        });
        updateButtonState();
    }

    function updateButtonState() {
        const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
        const updateButton = document.getElementById('updateButton');
        const deleteButton = document.getElementById('deleteButton');
        let isAnyChecked = false;

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                isAnyChecked = true;
            }
        });

        updateButton.disabled = !isAnyChecked;
        deleteButton.disabled = !isAnyChecked;
    }

    function updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('sa_id');
        const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
        let allChecked = true;

        checkboxes.forEach(function(checkbox) {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        selectAllCheckbox.checked = allChecked;
    }

    function confirmDelete() {
        const selectedIds = Array.from(document.querySelectorAll('input[name="selected_ids[]"]:checked')).map(cb => cb.value);
        if (selectedIds.length > 0) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteSelectedIds').value = selectedIds.join(',');
                    document.getElementById('deleteForm').submit();
                }
            });
        }
    }

    document.querySelectorAll('[data-confirm-delete]').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    });
</script>

@endsection