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
                                    <h5 class="card-title">Payment Table</h5>
                                    <div class="ms-auto">
                                        <div class="btn-toolbar float-end" role="toolbar">
                                            <button class="btn btn-dark w-100" data-bs-toggle="modal"
                                                data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i>Payment</button>
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
                                                <th>Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payment as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->py_name }}</td>
                                                    <td>{{ $item->py_value }}</td>
                                                    <td>
                                                        <div class="dropstart">
                                                            <a class="text-muted dropdown-toggle font-size-18"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" id="edit-btn"
                                                                    href="javascript:void(0)" data-id="{{ $item->py_id }}"
                                                                    data-payment-name="{{ $item->py_name }}"
                                                                    data-payment-value="{{ $item->py_value }}">Edit</a>
                                                                <a class="dropdown-item" data-confirm-delete="true"
                                                                    href="{{ route('payment.delete', $item->py_id) }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
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
                        <h5 class="modal-title" id="addDataModalTitle">Create New Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('payment.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="py_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="py_name" name="py_name"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="py_value" class="form-label">Value</label>
                                <input type="text" class="form-control" id="py_value" name="py_value"
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
                        <h5 class="modal-title" id="editDataModalTitle">Edit Payment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPaymentForm" action="" method="post">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <label for="edit_py_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_py_name" name="py_name"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_py_value" class="form-label">Value</label>
                                <input type="text" class="form-control" id="edit_py_value" name="py_value"
                                    placeholder="Enter Value" required>
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
                    var paymentId = this.getAttribute('data-id');
                    var paymentName = this.getAttribute('data-payment-name');
                    var paymentValue = this.getAttribute('data-payment-value');
                    var formAction = '{{ route('payment.update', ':id') }}';
                    formAction = formAction.replace(':id', paymentId);

                    document.getElementById('editPaymentForm').action = formAction;
                    document.getElementById('edit_py_name').value = paymentName;
                    document.getElementById('edit_py_value').value = paymentValue;

                    var editModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                    editModal.show();
                });
            });
        });

        // Auto fill value when add data
        document.getElementById('py_name').addEventListener('input', function() {
        const py_name = this.value;
        const py_value = py_name.toLowerCase().replace(/ /g, '-');
        document.getElementById('py_value').value = py_value;
        });

        // Auto fill value when edit data
        document.getElementById('edit_py_name').addEventListener('input', function() {
        const py_name = this.value;
        const py_value = py_name.toLowerCase().replace(/ /g, '-');
        document.getElementById('edit_py_value').value = py_value;
        });
    </script>
@endsection
