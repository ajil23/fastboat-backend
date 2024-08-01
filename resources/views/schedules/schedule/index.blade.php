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
                                <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                                    <thead>
                                        <div class="search-box">
                                            <div class="position-relative">
                                                <input type="search" name="search" class="form-control rounded bg-light border-0" placeholder="Search schedule..." id="search-input"><i class="bx bx-search search-icon"></i>
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
                                        <tr id="baris-{{$item->sch_id}}" class="search">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->company->cpn_name}}</td>
                                            <td class="search-item"  data-id="{{$item->sch_id}}">{{$item->sch_name}}</td>
                                            <td>
                                                <div class="dropstart">
                                                    <a class="text-muted dropdown-toggle font-size-18" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{route('schedule.edit', $item->sch_id)}}">Edit</a>
                                                        <a class="dropdown-item" data-confirm-delete="true" href="{{route('schedule.delete', $item->sch_id)}}" >Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    {{-- {{$scheduleData->links('pagination::bootstrap-5')}} --}}
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
                    <h5 class="modal-title" id="addDataModalTitle">Create New Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('schedule.store')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="sch_company" class="form-label">Company</label>
                            <select data-trigger name="sch_company" id="sch_company" required>
                                <option value="">Select Fast Boat Company</option>
                                @foreach ($company as $item)
                                    <option value="{{$item->cpn_id}}">{{$item->cpn_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sch_name" class="form-label">Schedule Name</label>
                            <input type="text" class="form-control" name="sch_name" id="sch_name" placeholder="Type the schedule name" required>
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
<!-- search box -->
<script>
    $(document).ready(function() {
        $('#search-input').on('input', function() {
            var search= $(this).val();
            var lowerCaseText = search.toLowerCase();
            var list = $('.search-item');
            // console.log(list);
            $('.search').show()
            list.each(function(){
                var item = $(this).text();
                var id = $(this).data('id');
                if(item.toLowerCase().includes(lowerCaseText)===false){
                    $('#baris-'+id).hide()
                }
            })
        })
    })
</script>

{{-- tom select --}}
<script>
    new TomSelect("#sch_company",{
	    sortField: {
		field: "text",
		direction: "asc"
	    }
    });
</script>
@endsection