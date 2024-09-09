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
                                    <h5 class="card-title">Currency Table</h5>
                                    <div class="ms-auto">
                                        <div class="btn-toolbar float-end" role="toolbar">
                                            <button class="btn btn-dark w-100" data-bs-toggle="modal"
                                                data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i>Currency</button>
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
                                                <th>Rate</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($currency as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->cy_name }}</td>
                                                    <td>{{ $item->cy_code }}</td>
                                                    <td>{{ number_format($item->cy_rate, 2, ',', '.') }}</td>

                                                    <td>
                                                        <a href="{{ route('currency.status', $item->cy_id) }}"
                                                            class="badge rounded-pill bg-{{ $item->cy_status ? 'success' : 'danger' }}"><i
                                                                class="mdi mdi-{{ $item->cy_status ? 'check-decagram' : 'alert-decagram' }}"></i>
                                                            {{ $item->cy_status ? 'Enable' : 'Disable' }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <div class="dropstart">
                                                            <a class="text-muted dropdown-toggle font-size-18"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" id="edit-btn"
                                                                    href="javascript:void(0)" data-id="{{ $item->cy_id }}"
                                                                    data-currency-name="{{ $item->cy_name }}"
                                                                    data-currency-code="{{ $item->cy_code }}"
                                                                    data-currency-rate="{{ $item->cy_rate }}">Edit</a>
                                                                <a class="dropdown-item" data-confirm-delete="true"
                                                                    href="{{ route('currency.delete', $item->cy_id) }}">Delete</a>
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
                        <h5 class="modal-title" id="addDataModalTitle">Create New Currency</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('currency.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cy_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="cy_name" name="cy_name"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="cy_code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="cy_code" name="cy_code"
                                    placeholder="Enter Value" required>
                            </div>
                            <div class="mb-3">
                                <label for="cy_rate" class="form-label">Rate</label>
                                <input type="text" class="form-control" id="cy_rate" name="cy_rate"
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
                        <h5 class="modal-title" id="editDataModalTitle">Edit Currency</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCurrencyForm" action="" method="post">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <label for="edit_cy_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit_cy_name" name="cy_name"
                                    placeholder="Enter Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_cy_code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="edit_cy_code" name="cy_code"
                                    placeholder="Enter Value" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_cy_rate" class="form-label">Rate</label>
                                <input type="text" class="form-control" id="edit_cy_rate" name="cy_rate"
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
        // Rate Format
        // Format number with dots as thousands separators and commas as decimal separator
function formatNumber(value) {
    let parts = value.split(',');
    let integerPart = parts[0];
    let decimalPart = parts.length > 1 ? parts[1] : '';
    
    integerPart = integerPart.replace(/\D/g, '');
    integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    
    if (decimalPart.length > 2) {
        decimalPart = decimalPart.substring(0, 2); // Limit decimal places to 2
    }
    
    return integerPart + (decimalPart ? ',' + decimalPart : '');
}

// Remove dots and commas for submission
function unformatNumber(value) {
    return value.replace(/\./g, '').replace(/,/g, '.');
}

// Example usage
document.getElementById('cy_rate').addEventListener('input', function(e) {
    e.target.value = formatNumber(e.target.value);
});

document.querySelector('form').addEventListener('submit', function() {
    const rateInput = document.getElementById('cy_rate');
    rateInput.value = unformatNumber(rateInput.value);
});


        // Modal edit 
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('#edit-btn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var currencyId = this.getAttribute('data-id');
                    var currencyName = this.getAttribute('data-currency-name');
                    var currencyCode = this.getAttribute('data-currency-code');
                    var currencyRate = this.getAttribute('data-currency-rate');
                    var formAction = '{{ route('currency.update', ':id') }}';
                    formAction = formAction.replace(':id', currencyId);

                    document.getElementById('editCurrencyForm').action = formAction;
                    document.getElementById('edit_cy_name').value = currencyName;
                    document.getElementById('edit_cy_code').value = currencyCode;
                    document.getElementById('edit_cy_rate').value = currencyRate;

                    var editModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                    editModal.show();
                });
            });
        });
    </script>
@endsection
