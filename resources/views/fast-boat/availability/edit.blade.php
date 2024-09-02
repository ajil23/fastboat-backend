@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{route('availability.update')}}" method="POST">
                @csrf
                <div id="results" class="row mt-2">
                    <div id="shuttle-info" class="col-lg-12">
                        <div id="addproduct-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Availability Add</h5>
                                                <p class="text-danger text-truncat font-size-12">*All price are in Indonesian Rupiah</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    <div class="p-4 border-top">
                                        <div class="row form-field" id="field-price">
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_adult_nett">Adult Nett*</label>
                                                    <input type="text" id="fba_adult_nett" name="fba_adult_nett" placeholder="Enter Adult Nett" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_child_nett">Child Nett*</label>
                                                    <input type="text" id="fba_child_nett" name="fba_child_nett" placeholder="Enter Child Nett" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_adult_publish">Adult Publish*</label>
                                                    <input type="text" id="fba_adult_publish" name="fba_adult_publish" placeholder="Enter Adult Publish" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_child_publish">Child Publish*</label>
                                                    <input type="text" id="fba_child_publish" name="fba_child_publish" placeholder="Enter Child Publish" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_discount">Discount*</label>
                                                    <input type="text" id="fba_discount" name="fba_discount" placeholder="Enter Discount Nominal" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div id="field-pax" class="col-lg-3 form-field">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_discount">Min Pax*</label>
                                                    <input type="text" id="fba_discount" name="fba_discount" placeholder="Enter Minimal Pax" class="form-control">
                                                </div>
                                            </div>
                                            <div id="field-stock" class="col-lg-3 form-field">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_stock">Stock*</label>
                                                    <input type="number" id="fba_stock" name="fba_stock" placeholder="Enter Stock" class="form-control">
                                                </div>
                                            </div>
                                            <div id="field-status" class="col-lg-3 form-field">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_status">Status*</label>
                                                    <select name="fba_status" id="fba_status" class="form-control">
                                                        <option value="enable">Enable</option>
                                                        <option value="disable">Disable</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="field-shuttle_status" class="col-lg-3 form-field">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_shuttle_status">Status Shuttle*</label>
                                                    <select name="fba_shuttle_status" id="fba_shuttle_status" class="form-control">
                                                        <option value="enable">Enable</option>
                                                        <option value="disable">Disable</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-field" id="field-custom-time">
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_dept_time">Departure Time*</label>
                                                    <input type="text" id="fba_dept_time" name="fba_dept_time" placeholder="Enter Departure Time" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fba_arriv_time">Arrival Time*</label>
                                                    <input type="text" id="fba_arriv_time" name="fba_arriv_time" placeholder="Enter Arrival Time" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="field-info" class="mb-3 form-field">
                                            <label class="form-label" for="fba_info">Info</label>
                                            <textarea class="form-control" id="fba_info" name="fba_info" placeholder="Enter Info Availability" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                {{-- Button --}}
                                <div class="row mb-4">
                                    <div class="col text-end">
                                        <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                                        <button type="submit" class="btn btn-dark"><i class="bx bx-file me-1"></i> Save</button>
                                    </div> <!-- end col -->
                                </div> <!-- end row-->
                                <!-- end row -->
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-centered align-middle table-nowrap mb-0 table-check">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Fast Boat</th>
                                    <th>Price</th>
                                    <th>Shuttle</th>
                                    <th>Status</th>
                                    <th>Stock</th>
                                    <th>Min Pax</th>
                                    <th>Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($availability as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                @endforeach
                            </tbody>
                            {{-- {{$fastboat->links('pagination::bootstrap-5')}} --}}
                        </table>
                    </div>
                </div>
            </div>

        </div>
        @include('admin.components.footer')
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Sembunyikan semua field terlebih dahulu
        $('.form-field').hide();

        // Ambil data field yang dipilih dari localStorage
        let selectedFields = JSON.parse(localStorage.getItem('selectedFields')) || [];

        // Tampilkan field yang dipilih
        selectedFields.forEach(function(fieldId) {
            let $field = $(`#field-${fieldId}`);
            if ($field.length) {
                $field.show();
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        function formatCurrencyInput(selector) {
            $(selector).on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                $(this).val(value);
            });

            $('form').on('submit', function() {
                const rawValue = $(selector).val().replace(/\./g, '');
                $(selector).val(rawValue);
            });
        }

        // Panggil fungsi untuk input yang diinginkan
        formatCurrencyInput('#fba_adult_nett');
        formatCurrencyInput('#fba_child_nett');
        formatCurrencyInput('#fba_adult_publish');
        formatCurrencyInput('#fba_child_publish');
        formatCurrencyInput('#fba_discount');
    });
</script>
@endsection