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
                                    <h5 class="card-title">Schedule Table</h5>
                                    <div class="ms-auto">
                                        <div class="btn-toolbar float-end" role="toolbar">
                                            <button class="btn btn-dark w-100" data-bs-toggle="modal"
                                                data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i>Schedule</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                        <thead>
                                            <div class="search-box">
                                                <div class="position-relative">
                                                    <input type="search" name="search"
                                                        class="form-control rounded bg-light border-0"
                                                        placeholder="Search schedule..." id="search-input"><i
                                                        class="bx bx-search search-icon"></i>
                                                </div>
                                            </div>
                                            <tr>
                                                <th>No</th>
                                                <th>Company</th>
                                                <th>Schedule Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($scheduleData as $item)
                                                <tr id="baris-{{ $item->sch_id }}" class="search">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->company->cpn_name }}</td>
                                                    <td class="search-item" data-id="{{ $item->sch_id }}">
                                                        {{ $item->sch_name }}</td>
                                                    <td>
                                                        <div class="dropstart">
                                                            <a class="text-muted dropdown-toggle font-size-18"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" id="edit-btn"
                                                                    href="javascript:void(0)" data-id="{{ $item->sch_id }}"
                                                                    data-schedule-company="{{ $item->sch_company }}"
                                                                    data-schedule-name="{{ $item->sch_name }}">Edit</a>
                                                                <a class="dropdown-item" data-confirm-delete="true"
                                                                    href="{{ route('schedule.delete', $item->sch_id) }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <input type="hidden" class="schedule-id" value="{{ $item->sch_id }}">
                                                    <input type="hidden" class="company-id"
                                                        value="{{ $item->company->cpn_id }}">
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

        <!-- Adding data modal -->
        <div class="modal fade" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addDataModalTitle">Create New Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('schedule.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="sch_company" class="form-label">Company</label>
                                <select data-trigger name="sch_company" id="sch_company" required>
                                    <option value="">Select Fast Boat Company</option>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->cpn_id }}">{{ $item->cpn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sch_name" class="form-label">Schedule Name</label>
                                <input type="text" class="form-control" name="sch_name" id="sch_name"
                                    placeholder="Type the schedule name" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save</button>
                    </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- Edit data modal -->
        <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog" aria-labelledby="editDataModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDataModalTitle">Edit Schedule</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editScheduleForm" action="" method="post">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <label for="sch_company" class="form-label">Company</label>
                                <select data-trigger name="sch_company" id="sch_company_edit"
                                    required>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->cpn_id }}">{{ $item->cpn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sch_name" class="form-label">Schedule Name</label>
                                <input type="text" class="form-control" name="sch_name" id="sch_name_edit"
                                    placeholder="Type the schedule name" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-dark">Save</button>
                    </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @include('admin.components.footer')
    </div>
@endsection

@section('script')
    <script>
        // search box
        $(document).ready(function() {
            $('#search-input').on('input', function() {
                var search = $(this).val();
                var lowerCaseText = search.toLowerCase();
                var list = $('.search-item');
                $('.search').show();
                list.each(function() {
                    var item = $(this).text();
                    var id = $(this).data('id');
                    if (item.toLowerCase().includes(lowerCaseText) === false) {
                        $('#baris-' + id).hide();
                    }
                });
            });
        });

        // tom select
        new TomSelect("#sch_company", {
            sortField: {
                field: "text",
                direction: "asc"
            }
        });

        new TomSelect("#sch_company_edit", {});

        // edit modal
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('#edit-btn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var scheduleId = this.getAttribute('data-id');
                    var companyId = this.getAttribute('data-schedule-company');
                    var scheduleName = this.getAttribute('data-schedule-name');
                    var formAction = '{{ route('schedule.update', ':id') }}';
                    formAction = formAction.replace(':id', scheduleId);

                    var selectCompanyInstance = document.getElementById('sch_company_edit')
                        .tomselect;
                    selectCompanyInstance.setValue(companyId);

                    document.getElementById('editScheduleForm').action = formAction;
                    document.getElementById('sch_company_edit').value = companyId;
                    document.getElementById('sch_name_edit').value = scheduleName;

                    var editModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                    editModal.show();
                });
            });
        });

        // $(document).ready(function() {
        //     // Event handler untuk tombol edit
        //     $('body').on('click', '.dropdown-item[data-bs-target="#editDataModal"]', function() {
        //         // Ambil ID dan data lain dari baris yang dipilih
        //         var tr = $(this).closest('tr');
        //         var id = tr.attr('id').replace('baris-', '');
        //         var scheduleName = tr.find('.search-item').text();
        //         var companyId = tr.find('.company-id').val();
        //         var scheduleId = tr.find('.schedule-id').val();

        //         // Isi modal dengan data yang sesuai
        //         $('#editDataModal #sch_name_edit').val(scheduleName);
        //         $('#editDataModal #sch_company_edit').val(companyId);

        //         // Ubah action form untuk update sesuai dengan id
        //         var formAction = '{{ route('schedule.update', ':id') }}';
        //         formAction = formAction.replace(':id', scheduleId);

        //         $('#editScheduleForm').attr('action', formAction);

        //         // Set value dropdown secara manual dan trigger change
        //         $('#sch_company_edit').val(companyId).trigger('change');
        //     });
        // });
    </script>
@endsection
