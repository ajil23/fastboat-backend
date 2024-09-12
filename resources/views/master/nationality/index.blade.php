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
                                    <h5 class="card-title">Nationality Table</h5>
                                    <div class="ms-auto">
                                        <div class="btn-toolbar float-end" role="toolbar">
                                            <button class="btn btn-dark w-100" data-bs-toggle="modal"
                                                data-bs-target="#addDataModal"><i
                                                    class="mdi mdi-plus"></i>Nationality</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($nationality as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->nas_country }}</td>
                                                    <td>{{ $item->nas_country_code }}</td>
                                                    <td>
                                                        <div class="dropstart">
                                                            <a class="text-muted dropdown-toggle font-size-18"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" id="edit-btn"
                                                                    href="javascript:void(0)" data-id="{{ $item->nas_id }}"
                                                                    data-nationality-name="{{ $item->nas_country }}"
                                                                    data-nationality-code="{{ $item->nas_country_code }}">Edit</a>
                                                                <a class="dropdown-item" data-confirm-delete="true"
                                                                    href="{{ route('nationality.delete', $item->nas_id) }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
                                    {{ $nationality->links('pagination::bootstrap-4') }}
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
                        <h5 class="modal-title" id="addDataModalTitle">Create New Nationality</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('nationality.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nas_country" class="form-label">Name</label>
                                <input type="text" class="form-control" id="nas_country" name="nas_country"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="nas_country_code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="nas_country_code" name="nas_country_code"
                                    placeholder="Enter Value" required>
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

        <!-- Editing data modal -->
        <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog" aria-labelledby="editDataModalTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDataModalTitle">Edit Nationality</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editNationalityForm" action="" method="post">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <label for="edit_nas_country" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_nas_country" name="nas_country"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_nas_country_code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="edit_nas_country_code"
                                    name="nas_country_code" placeholder="Enter Value" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-dark">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.components.footer')
    </div>
@endsection


@section('script')
    <script>
        // Modal edit 
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('#edit-btn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var nationalityId = this.getAttribute('data-id');
                    var nationalityName = this.getAttribute('data-nationality-name');
                    var nationalityCode = this.getAttribute('data-nationality-code');
                    var formAction = '{{ route('nationality.update', ':id') }}';
                    formAction = formAction.replace(':id', nationalityId);

                    document.getElementById('editNationalityForm').action = formAction;
                    document.getElementById('edit_nas_country').value = nationalityName;
                    document.getElementById('edit_nas_country_code').value = nationalityCode;

                    var editModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                    editModal.show();
                });
            });
        });
    </script>
@endsection
