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
                                    <h5 class="card-title">Checkin Point Table</h5>
                                    <div class="ms-auto">
                                        <div class="btn-toolbar float-end" role="toolbar">
                                            <button class="btn btn-dark w-100" data-bs-toggle="modal"
                                                data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i>Point</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Company</th>
                                                <th>Departure Port</th>
                                                <th>Address</th>
                                                <th>Map</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($checkin as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->company->cpn_name }}</td>
                                                    <td>{{ $item->departure->prt_name_en }}</td>
                                                    <td>{{ Str::limit($item->fcp_address, 10) }}</td>
                                                    <td>
                                                        <a href="{{ $item->fcp_maps }}"
                                                            class="badge bg-success-subtle text-success  font-size-12"
                                                            target="blank">
                                                            See<i class="mdi mdi-arrow-right"></i></a>
                                                    </td>
                                                    <td>
                                                        <div class="dropstart">
                                                            <a class="text-muted dropdown-toggle font-size-18"
                                                                role="button" data-bs-toggle="dropdown"
                                                                aria-haspopup="true">
                                                                <i class="mdi mdi-dots-horizontal"></i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                    id="showDetail"
                                                                    data-url="{{route('checkin.show', $item->fcp_id)}}">View</a>
                                                                <a class="dropdown-item" id="edit-btn"
                                                                    href="javascript:void(0)" data-id="{{ $item->fcp_id }}"
                                                                    data-point-company="{{ $item->fcp_company }}"
                                                                    data-point-departure="{{ $item->fcp_dept }}"
                                                                    data-point-address="{{ $item->fcp_address }}"
                                                                    data-point-maps="{{ $item->fcp_maps }}">Edit</a>
                                                                <a class="dropdown-item" data-confirm-delete="true"
                                                                    href="{{ route('checkin.delete', $item->fcp_id) }}">Delete</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <br>
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
                        <h5 class="modal-title" id="addDataModalTitle">Create New Point</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('checkin.store') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="fcp_company" class="form-label">Company</label>
                                <select name="fcp_company" id="fcp_company">
                                    <option value="">Select Company</option>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->cpn_id }}">{{ $item->cpn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fcp_dept" class="form-label">Departure Port</label>
                                <select name="fcp_dept" id="fcp_dept">
                                    <option value="">Select Port</option>
                                    @foreach ($departure as $item)
                                        <option value="{{ $item->prt_id }}">{{ $item->prt_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="fcp_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="fcp_address" name="fcp_address"
                                    placeholder="Enter address" required>
                            </div>
                            <div class="mb-3">
                                <label for="fcp_maps" class="form-label">Maps</label>
                                <input type="text" class="form-control" id="fcp_maps" name="fcp_maps"
                                    placeholder="Enter maps link" required>
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
                        <h5 class="modal-title" id="editDataModalTitle">Edit Checkin Point</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editChekingPointForm" action="" method="post">
                            @csrf
                            @method('POST')
                            <div class="mb-3">
                                <label for="edit_fcp_company" class="form-label">Company</label>
                                <select name="fcp_company" id="edit_fcp_company">
                                    <option value="">Select Company</option>
                                    @foreach ($company as $item)
                                        <option value="{{ $item->cpn_id }}">{{ $item->cpn_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_fcp_dept" class="form-label">Departure Port</label>
                                <select name="fcp_dept" id="edit_fcp_dept">
                                    <option value="">Select Port</option>
                                    @foreach ($departure as $item)
                                        <option value="{{ $item->prt_id }}">{{ $item->prt_name_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="edit_fcp_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="edit_fcp_address" name="fcp_address"
                                    placeholder="Enter address" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_fcp_maps" class="form-label">Maps</label>
                                <input type="text" class="form-control" id="edit_fcp_maps" name="fcp_maps"
                                    placeholder="Enter maps link" required>
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

        <!-- Scrollable modal -->
        <div class="modal fade" id="viewDetailModal" tabindex="-1" role="dialog"
            aria-labelledby="viewDetailModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewDetailModalTitle">Cheking Point Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Company : </strong><span id="company"></span></p>
                        <p><strong>Departure Port : </strong><span id="port"></span></p>
                        <p><strong>Address : </strong><span id="address"></span></p>
                        <p><strong>Map : </strong><span id="map"></span></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        @include('admin.components.footer')
    </div>
@endsection


@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('body').on('click', '#showDetail', function() {
                var detailURL = $(this).data('url');
                $.get(detailURL, function(data) {
                    $('#viewDetailModal').modal('show');
                    $('#company').text(data.company.cpn_name);
                    $('#port').text(data.departure.prt_name_en);
                    $('#address').text(data.fcp_address);
                    $('#map').text(data.fcp_maps);
                })
            })
        });
    </script>
    <script>
        new TomSelect("#fcp_company");
        new TomSelect("#fcp_dept");
        new TomSelect("#edit_fcp_company", {});
        new TomSelect("#edit_fcp_dept", {});

        // Modal edit 
        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('#edit-btn');
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var pointId = this.getAttribute('data-id');
                    var pointCompany = this.getAttribute('data-point-company');
                    var pointDeparture = this.getAttribute('data-point-departure');
                    var pointAddress = this.getAttribute('data-point-address');
                    var pointMaps = this.getAttribute('data-point-maps');

                    var formAction = '{{ route("checkin.update", ":id") }}';
                    formAction = formAction.replace(':id', pointId);

                    // Update form action
                    document.getElementById('editChekingPointForm').action = formAction;

                    // Menggunakan Tom Select untuk pointCompany
                    var selectCompanyInstance = document.getElementById('edit_fcp_company')
                        .tomselect;
                    selectCompanyInstance.setValue(pointCompany);

                    // Menggunakan Tom Select untuk pointDeparture
                    var selectDepartureInstance = document.getElementById('edit_fcp_dept')
                        .tomselect;
                    selectDepartureInstance.setValue(pointDeparture);

                    // Mengatur value untuk alamat dan maps tanpa Tom Select
                    document.getElementById('edit_fcp_address').value = pointAddress;
                    document.getElementById('edit_fcp_maps').value = pointMaps;

                    // Menampilkan modal untuk mengedit data
                    var editModal = new bootstrap.Modal(document.getElementById('editDataModal'));
                    editModal.show();
                });
            });
        });
    </script>
@endsection
