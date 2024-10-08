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
                                <h5 class="card-title">Shuttle Area Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <button class="btn btn-dark w-100" data-bs-toggle="modal" data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i>Shuttle Area</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Name</th>
                                            <th>Island</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shuttlearea as $item)
                                        <tr id="baris-{{$item->sa_id}}" class="shuttleArea">
                                            <td class="fw-semibold">{{$loop->iteration}}</td>
                                            <td class="item" data-id="{{$item->sa_id}}">{{$item->sa_name}}</td>
                                            <td>{{$item->island->isd_name}}</td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        {{-- <a class="dropdown-item" href="{{route('shuttlearea.edit', $item->sa_id)}}">Edit</a> --}}
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editDataModal" data-url="{{route('shuttlearea.edit', $item->sa_id)}}">Edit</a>
                                                        <a class="dropdown-item" data-confirm-delete="true" href="{{route('shuttlearea.delete', $item->sa_id)}}">Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <input type="hidden" class="shuttlearea-id" value="{{$item->sa_id}}">
                                            <input type="hidden" class="island-id" value="{{$item->island->isd_id}}">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <br>
                                {{$shuttlearea->links('pagination::bootstrap-4')}}
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
    <div class="modal fade" id="addDataModal" tabindex="-1" role="dialog" aria-labelledby="addDataModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDataModalTitle">Create New Shuttle Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('shuttlearea.store')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="sa_name" class="form-label">Shuttle Area</label>
                            <input type="text" class="form-control" name="sa_name" id="sa_name" placeholder="Type the schedule name" required>
                        </div>
                        <div class="mb-3">
                            <label for="sa_island" class="form-label">Island</label>
                            <select class="form-control" data-trigger name="sa_island" id="sa_island" required>
                                <option value="">Select Island</option>
                                @foreach ($island as $item)
                                <option value="{{$item->isd_id}}">{{$item->isd_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <!-- Edit data modal -->
    <div class="modal fade" id="editDataModal" tabindex="-1" role="dialog" aria-labelledby="editDataModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDataModalTitle">Edit Shuttle Area</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editShuttlAreaForm" action="" method="post">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="sa_name" class="form-label">Shuttle Area</label>
                            <input type="text" class="form-control" name="sa_name" id="sa_name_edit" placeholder="Type the schedule name" required>
                        </div>
                        <div class="mb-3">
                            <label for="sa_island" class="form-label">Island</label>
                            <select class="form-control" data-trigger name="sa_island" id="sa_island_edit" required>
                                <option value="">Select Island</option>
                                @foreach ($island as $item)
                                <option value="{{$item->isd_id}}">{{$item->isd_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-dark">Save</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @include('admin.components.footer')
</div>
@endsection
@section('script')
    <script>
    $(document).ready(function() {
        // Event handler untuk tombol edit
        $('body').on('click', '.dropdown-item[data-bs-target="#editDataModal"]', function() {
            // Ambil ID dan data lain dari baris yang dipilih
            var tr = $(this).closest('tr');
            var id = tr.attr('id').replace('baris-', '');
            var shuttleareaName = tr.find('.item').text();
            var shuttleareaId = tr.find('.shuttlearea-id').val();
            var islandId = tr.find('.island-id').val();
            
            // Isi modal dengan data yang sesuai
            $('#editDataModal #sa_name_edit').val(shuttleareaName);
            $('#editDataModal #sa_island_edit').val(islandId);
    
            // Ubah action form untuk update sesuai dengan id
            var formAction = '{{ route("shuttlearea.update", ":id") }}';
            formAction = formAction.replace(':id', shuttleareaId);

            $('#editShuttlAreaForm').attr('action', formAction);
    
            // Set value dropdown secara manual dan trigger change
            $('#sa_island_edit').val(islandId).trigger('change');
        });
    });
    </script>
@endsection