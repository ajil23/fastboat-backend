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
                                <h5 class="card-title">Route Table</h5>
                                <div class="ms-auto">
                                    <div class="btn-toolbar float-end" role="toolbar">
                                        <button class="btn btn-dark w-100" data-bs-toggle="modal"
                                        data-bs-target="#addDataModal"><i class="mdi mdi-plus"></i> Create New Route</button>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Departured</th>
                                            <th>Arrival</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($route as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->rt_dept_island}}</td>
                                            <td>{{$item->rt_arrival_island}}</td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{route('route.edit', $item->rt_id)}}">Edit</a>
                                                        <a class="dropdown-item" data-confirm-delete="true" href="{{route('route.delete', $item->rt_id)}}">Delete</a>
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
     <div class="modal fade" id="addDataModal" tabindex="-1" role="dialog"
     aria-labelledby="addDataModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="addDataModalTitle">Create New Route</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <form action="{{route('route.store')}}" method="post">
                     @csrf
                     <div class="mb-3">
                         <label for="sch_company" class="form-label">Departured Island</label>
                         <input type="text" class="form-control" id="rt_dept_island" name="rt_dept_island" placeholder="Enter Departured Island" required>
                     </div>
                     <div class="mb-3">
                         <label for="sch_name" class="form-label">Arrival Island</label>
                         <input type="text" class="form-control" nid="rt_arrival_island" name="rt_arrival_island" placeholder="Enter Arrival Island" required>
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