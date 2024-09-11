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
                                        <div class="col text-end">
                                            <form action="{{ route('currency.updateKurs') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-dark waves-effect waves-light">
                                                    <i class="mdi mdi-pencil"></i> Update
                                                </button>
                                            </form>
                                            <button class="btn btn-dark" data-bs-toggle="modal"
                                                data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i>Add</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Name (code)</th>
                                                <th>Rate</th>
                                                <th>Status</th>
                                                <th>Updated by</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($currency as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->cy_name }} ({{ $item->cy_code }}) </td>
                                                    <td>{{ number_format($item->cy_rate, 2, ',', '.') }}</td>
                                                    <td>
                                                        <a href="{{ route('currency.status', $item->cy_id) }}"
                                                            class="badge rounded-pill bg-{{ $item->cy_status ? 'success' : 'danger' }}"><i
                                                                class="mdi mdi-{{ $item->cy_status ? 'check-decagram' : 'alert-decagram' }}"></i>
                                                            {{ $item->cy_status ? 'Enable' : 'Disable' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->cy_updated_by }} -
                                                        ({{ date('d/m/Y - H:i:s', strtotime($item->updated_at)) }})
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
    </script>
@endsection
