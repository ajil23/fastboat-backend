@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{ route('availability.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div id="results" class="row mt-2">
                    <div id="shuttle-info" class="col-lg-12">
                        <div id="addproduct-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Edit Availability</h5>
                                                <p class="text-danger text-truncate font-size-12">*All prices are in Indonesian Rupiah</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        @if($availabilities->isNotEmpty() && !empty($selectedFields))
                                        @foreach($selectedFields as $field)
                                        <input type="hidden" name="selected_availability_ids[]" value="{{ implode(',', $availabilities->pluck('fba_id')->toArray()) }}">
                                        <div class="row">
                                            @if(in_array('price', $selectedFields))
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_adult_nett">Adult Nett*</label>
                                                    <input type="text" id="fba_adult_nett" name="fba_adult_nett" placeholder="Enter Adult Nett" class="form-control" value="{{ number_format($availabilities->first()->fba_adult_nett, 0, ',', '.') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_child_nett">Child Nett*</label>
                                                    <input type="text" id="fba_child_nett" name="fba_child_nett" placeholder="Enter Child Nett" class="form-control" value="{{ number_format($availabilities->first()->fba_child_nett, 0, ',', '.') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_adult_publish">Adult Publish*</label>
                                                    <input type="text" id="fba_adult_publish" name="fba_adult_publish" placeholder="Enter Adult Publish" class="form-control" value="{{ number_format($availabilities->first()->fba_adult_publish, 0, ',', '.') }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_child_publish">Child Publish*</label>
                                                    <input type="text" id="fba_child_publish" name="fba_child_publish" placeholder="Enter Child Publish" class="form-control" value="{{ number_format($availabilities->first()->fba_child_publish, 0, ',', '.') }}">
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row">
                                            @if(in_array('price', $selectedFields))
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_discount">Discount*</label>
                                                    <input type="text" id="fba_discount" name="fba_discount" placeholder="Enter Discount Nominal" class="form-control" value="{{ number_format($availabilities->first()->fba_discount, 0, ',', '.') }}">
                                                </div>
                                            </div>
                                            @endif

                                            @if(in_array('stock', $selectedFields))
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_stock">Stock*</label>
                                                    <input type="number" id="fba_stock" name="fba_stock" placeholder="Enter Stock" class="form-control" value="{{ $availabilities->first()->fba_stock }}">
                                                </div>
                                            </div>
                                            @endif

                                            @if(in_array('available-status', $selectedFields))
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_status">Status*</label>
                                                    <select name="fba_status" id="fba_status" class="form-control">
                                                        <option value="enable" {{ $availabilities->first()->fba_status == 'enable' ? 'selected' : '' }}>Enable</option>
                                                        <option value="disable" {{ $availabilities->first()->fba_status == 'disable' ? 'selected' : '' }}>Disable</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @endif

                                            @if(in_array('shuttle-status', $selectedFields))
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_shuttle_status">Shuttle Status*</label>
                                                    <select name="fba_shuttle_status" id="fba_shuttle_status" class="form-control">
                                                        <option value="enable" {{ $availabilities->first()->fba_shuttle_status == 'enable' ? 'selected' : '' }}>Enable</option>
                                                        <option value="disable" {{ $availabilities->first()->fba_shuttle_status == 'disable' ? 'selected' : '' }}>Disable</option>
                                                    </select>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            @if(in_array('pax', $selectedFields))
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_min_pax">Min Pax*</label>
                                                    <input type="text" id="fba_min_pax" name="fba_min_pax" placeholder="Enter Minimal Pax" class="form-control" value="{{ $availabilities->first()->fba_min_pax }}">
                                                </div>
                                            </div>
                                            @endif

                                            @if(in_array('custom-time', $selectedFields))
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_dept_time">Departure Time*</label>
                                                    <input type="time" id="fba_dept_time" name="fba_dept_time" placeholder="Enter Departure Time" class="form-control" value="{{ $availabilities->first()->fba_dept_time }}">
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_arriv_time">Arrival Time*</label>
                                                    <input type="time" id="fba_arriv_time" name="fba_arriv_time" placeholder="Enter Arrival Time" class="form-control" value="{{ $availabilities->first()->fba_arriv_time }}">
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        @if(in_array('info', $selectedFields))
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="fba_info">Info</label>
                                                <textarea class="form-control" id="fba_info" name="fba_info" placeholder="Enter Info Availability" rows="4">{{ $availabilities->first()->fba_info }}</textarea>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @else
                                        <p>No data selected for update.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col text-end">
                        <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                        <button type="submit" class="btn btn-dark"><i class="bx bx-file me-1"></i> Save</button>
                    </div> <!-- end col -->
                </div>
            </form>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-centered align-middle table-nowrap mb-0 table-check">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Fast Boat</th>
                                    @if(in_array('price', $selectedFields))
                                    <th colspan="4" class="text-center">Price</th>
                                    @endif
                                    @if(in_array('shuttle-status', $selectedFields))
                                    <th>
                                        <center>Shuttle</center>
                                    </th>
                                    @endif
                                    @if(in_array('available-status', $selectedFields))
                                    <th>
                                        <center>Status</center>
                                    </th>
                                    @endif
                                    @if(in_array('stock', $selectedFields))
                                    <th>
                                        <center>Stock</center>
                                    </th>
                                    @endif
                                    @if(in_array('pax', $selectedFields))
                                    <th>
                                        <center>Min Pax</center>
                                    </th>
                                    @endif
                                    @if(in_array('info', $selectedFields))
                                    <th>
                                        <center>Info</center>
                                    </th>
                                    @endif
                                </tr>
                                <tr>
                                    @if(in_array('price', $selectedFields))
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>Nett</th>
                                    <th>Publish</th>
                                    <th>Discount</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($availabilities as $key => $availability)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                        {{ $availability->trip->fastboat->fb_name }}<br />
                                        {{$availability->trip->departure->prt_name_en}} - {{$availability->trip->arrival->prt_name_en}} ({{ \Carbon\Carbon::parse($availability->trip->fbt_dept_time)->format('H:i') }})
                                    </td>
                                    @if(in_array('price', $selectedFields))
                                    <td>
                                        <div>Adult :</div>
                                        <div>Child :</div>
                                    </td>
                                    <td>
                                        <div>{{ number_format($availability->fba_adult_nett, 0, ',', '.') }}</div>
                                        <div>{{ number_format($availability->fba_child_nett, 0, ',', '.') }}</div>
                                    </td>
                                    <td>
                                        <div>{{ number_format($availability->fba_adult_publish, 0, ',', '.') }}</div>
                                        <div>{{ number_format($availability->fba_child_publish, 0, ',', '.') }}</div>
                                    </td>
                                    <td>{{ number_format($availability->fba_discount, 0, ',', '.') }}</td>
                                    @endif
                                    @if(in_array('shuttle-status', $selectedFields))
                                    <td>
                                        <center>{{ $availability->fba_shuttle_status }}</center>
                                    </td>
                                    @endif
                                    @if(in_array('available-status', $selectedFields))
                                    <td>
                                        <center>{{ $availability->fba_status }}</center>
                                    </td>
                                    @endif
                                    @if(in_array('stock', $selectedFields))
                                    <td>
                                        <center>{{ $availability->fba_stock }}</center>
                                    </td>
                                    @endif
                                    @if(in_array('pax', $selectedFields))
                                    <td>
                                        <center>{{ $availability->fba_min_pax }}</center>
                                    </td>
                                    @endif
                                    @if(in_array('info', $selectedFields))
                                    <td>
                                        <center>{{$availability->fba_info}}</center>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end table -->
        </div>
        @include('admin.components.footer')
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {

        // Fungsi untuk format input mata uang
        function formatCurrencyInput(selector) {
            $(selector).on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(value);
            });

            $('form').on('submit', function() {
                $(selector).each(function() {
                    let rawValue = $(this).val().replace(/\./g, '');
                    $(this).val(rawValue);
                });
            });
        }

        // Panggil fungsi untuk input yang diinginkan
        @foreach($availabilities as $availability)
        @if(in_array('price', $selectedFields))
        formatCurrencyInput('#fba_adult_nett_{{ $availability->fba_id }}');
        formatCurrencyInput('#fba_child_nett_{{ $availability->fba_id }}');
        formatCurrencyInput('#fba_adult_publish_{{ $availability->fba_id }}');
        formatCurrencyInput('#fba_child_publish_{{ $availability->fba_id }}');
        formatCurrencyInput('#fba_discount_{{ $availability->fba_id }}');
        @endif
        @endforeach
    });
</script>
@endsection