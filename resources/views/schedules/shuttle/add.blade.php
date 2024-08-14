@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div id="addproduct-accordion">
                        <div class="card">
                            <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                <div class="p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Shuttle Search</h5>
                                            <p class="text-muted text-truncate mb-0">Search the shuttle</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                <div class="p-4 border-top">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Company</label>
                                                <select name="cpn_name" id="search-company">
                                                    <option value="">Select fast boat company</option>
                                                    @foreach ($company as $item)
                                                    <option value="{{ $item->cpn_name }}">{{ $item->cpn_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Departure Port</label>
                                                <select name="prt_name_dept" id="search-departure">
                                                    <option value="">Select Departure Port</option>
                                                    @foreach ($trip as $item)
                                                    <option value="{{ $item->departure->prt_name_en }}">{{ $item->departure->prt_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Arrival Port</label>
                                                <select name="prt_name_arrival" id="search-arrival">
                                                    <option value="">Select Arrival Port</option>
                                                    @foreach ($trip as $item)
                                                    <option value="{{ $item->arrival->prt_name_en }}">{{ $item->arrival->prt_name_en }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="mb-3">
                                                <label class="form-label">Option</label>
                                                <select name="prt_option" id="search-option">
                                                    <option value="">Select Shuttle Option</option>
                                                    @foreach ($trip as $item)
                                                    <option value="{{ $item->fbt_shuttle_option }}">{{ $item->fbt_shuttle_option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{route('shuttle.store')}}" method="POST">
                @csrf
                <div id="results" class="row mt-2">
                    <div class="form-check font-size-16" id="selectAlltrips" style="display: none;">
                        <input type="checkbox" class="form-check-input" id="select-all-trip">
                        <label for="select-all-trip">Select All</label>
                    </div>
                    @foreach ($trip as $item)
                    <div class="col-xl-4 col-sm-6 card-item" data-company="{{$item->schedule->company->cpn_name}}" data-departure="{{$item->departure->prt_name_en}}" data-arrival="{{$item->arrival->prt_name_en}}" data-option="{{$item->fbt_shuttle_option}}" style="display: none;"> <!-- Sembunyikan card secara default -->
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="{{$item->fbt_id}}" name="s_trip[]" id="trip + {{$item->fbt_id}}">
                                    <label class="form-check-label" for="trip + {{$item->fbt_id}}">
                                        {{$item->schedule->company->cpn_name}}
                                    </label>
                                </div>
                                <div class="mt-3 pt-1">
                                    <p>From : {{$item->departure->prt_name_en}}, {{$item->departure->island->isd_name}} ({{date('H:i', strtotime($item->fbt_dept_time))}})</p>
                                    <p>To : {{$item->arrival->prt_name_en}}, {{$item->arrival->island->isd_name}} ({{date('H:i', strtotime($item->fbt_arrival_time))}})</p>
                                    <p hidden>Option : {{$item->fbt_shuttle_option}}</p>
                                </div>
                            </div>
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                    @endforeach
                    <div id="shuttle-info" class="col-lg-12" style="display: none;"> <!-- Sembunyikan tabel shuttle info secara default -->
                        <div id="addproduct-accordion">
                            <div class="card">
                                <a class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1"> Shuttle Info</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show">
                                    <table class="table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col" class="ps-4" style="width: 50px;">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="form-check-input" id="select-all">
                                                    </div>
                                                </th>
                                                <th>
                                                    <center>Area</center>
                                                </th>
                                                <th>
                                                    <center>Start</center>
                                                </th>
                                                <th>
                                                    <center>End</center>
                                                </th>
                                                <th>
                                                    <center>Meeting Point</center>
                                                </th>
                                                <th>
                                                    <center>Note</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($area as $index => $item)
                                            <tr>
                                                <th scope="row" class="ps-4">
                                                    <div class="form-check font-size-16">
                                                        <input type="checkbox" class="form-check-input" name="selected_ids[]" data-row="{{ $index }}">
                                                    </div>
                                                </th>
                                                <td>
                                                    <center>
                                                        <input type="text" class="form-control input_info" value="{{$item->sa_name}}" readonly>
                                                        <input type="hidden" class="form-control input_info" value="{{$item->sa_id}}" name="s_area[]">
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <input type="time" class="form-control input_info" name="s_start[]">
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <input type="time" class="form-control input_info" name="s_end[]">
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <div class="form-check form-switch" style="display: flex; align-items: center;justify-content: center;">
                                                            <input class="form-check-input" style="width: 3rem; height: 1.75rem; border-radius: 1rem;" type="checkbox" id="switch" name="meeting_point_switch[]" />
                                                        </div>
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <input id="s_meeting_point" name="s_meeting_point[]" placeholder="Note/Meeting Point Location" type="text" class="form-control" disabled></input>
                                                    </center>
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


                {{-- Button --}}
                <div class="row mb-4">
                    <div class="col text-end">
                        <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                        <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Save</button>
                    </div> <!-- end col -->
                </div> <!-- end row-->
                <!-- end row -->

            </form>

        </div>
        @include('admin.components.footer')
    </div>
</div>
@endsection

@section('script')

<!-- script untuk mengatur checkbox shuttle info -->
<script>
    $(document).ready(function() {
    // Fungsi untuk mengaktifkan atau menonaktifkan input berdasarkan status checkbox
    function updateInputs() {
        $('input[name="selected_ids[]"]').each(function() {
            // Temukan baris terkait dengan checkbox
            const rowIndex = $(this).data('row');
            const inputs = $(`tr:nth-child(${parseInt(rowIndex) + 1}) .input_info`);

            // Aktifkan atau nonaktifkan input berdasarkan status checkbox
            inputs.prop('disabled', !$(this).is(':checked'));
        });
    }

    // Fungsi untuk memilih atau membatalkan semua checkbox
    function toggleSelectAll() {
        const isChecked = $('#select-all').is(':checked');
        $('input[name="selected_ids[]"]').prop('checked', isChecked);
        updateInputs();
    }

    // Fungsi untuk mengatur status checkbox "Pilih Semua"
    function updateSelectAllStatus() {
        const allChecked = $('input[name="selected_ids[]"]:checked').length === $('input[name="selected_ids[]"]').length;
        $('#select-all').prop('checked', allChecked);
    }

    // Tambahkan event listener untuk setiap checkbox
    $('input[name="selected_ids[]"]').on('change', function() {
        updateInputs();
        updateSelectAllStatus(); // Update status "Pilih Semua" setiap kali checkbox diubah
    });

    // Tambahkan event listener untuk checkbox 'Pilih Semua'
    $('#select-all').on('change', toggleSelectAll);

    // Panggil updateInputs pada awal untuk menyesuaikan status awal
    updateInputs();
});

</script>



<script>
    // mengatur tampilan pencarian
    new TomSelect("#search-company");
    new TomSelect("#search-departure");
    new TomSelect("#search-arrival");
    new TomSelect("#search-option");

    // switch button meeting point
$(document).ready(function() {
    const $form = $('form');
    const $checkboxes = $('input[name="selected_ids[]"]');
    const $switchElements = $('input[name="meeting_point_switch[]"]');
    const $textInputs = $('input[name="s_meeting_point[]"]');
    const $startInputs = $('input[name="s_start[]"]');
    const $endInputs = $('input[name="s_end[]"]');
    const $selectAllCheckbox = $('#select-all');

    // Fungsi untuk mengaktifkan atau menonaktifkan input berdasarkan status checkbox
    function updateInputs() {
        $checkboxes.each(function(index) {
            const isChecked = $(this).is(':checked');
            $switchElements.eq(index).prop('disabled', !isChecked);
            $textInputs.eq(index).prop('disabled', !isChecked || !$switchElements.eq(index).is(':checked'));
            $startInputs.eq(index).prop('disabled', !isChecked);
            $endInputs.eq(index).prop('disabled', !isChecked);
        });
    }

    // Fungsi untuk mengaktifkan semua switch jika checkbox select-all dicentang
    function updateSelectAll() {
        const isChecked = $selectAllCheckbox.is(':checked');
        $checkboxes.each(function(index) {
            $(this).prop('checked', isChecked);
            $switchElements.eq(index).prop('disabled', !isChecked);
            $textInputs.eq(index).prop('disabled', !isChecked || !$switchElements.eq(index).is(':checked'));
            $startInputs.eq(index).prop('disabled', !isChecked);
            $endInputs.eq(index).prop('disabled', !isChecked);
        });
    }

    // Event listener untuk checkbox individual
    $checkboxes.on('change', function() {
        updateInputs();
        updateSelectAllStatus();
    });

    // Event listener untuk switch
    $switchElements.on('change', function() {
        const index = $switchElements.index(this);
        $textInputs.eq(index).prop('disabled', !$(this).is(':checked'));
    });

    // Event listener untuk checkbox select-all
    $selectAllCheckbox.on('change', function() {
        updateSelectAll();
    });

    // Fungsi untuk memperbarui status checkbox select-all
    function updateSelectAllStatus() {
        const visibleCheckboxes = $checkboxes.filter(':visible');
        const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.filter(':checked').length === visibleCheckboxes.length;
        $selectAllCheckbox.prop('checked', allChecked);
    }

    // Nonaktifkan input yang tidak dipilih sebelum form di-submit
    $form.on('submit', function(e) {
        $checkboxes.each(function(index) {
            if (!$(this).is(':checked')) {
                // Nonaktifkan input yang tidak dipilih checkbox-nya
                $switchElements.eq(index).prop('disabled', true);
                $textInputs.eq(index).prop('disabled', true);
                $startInputs.eq(index).prop('disabled', true);
                $endInputs.eq(index).prop('disabled', true);
            }
        });
    });

    // Panggil updateInputs dan updateSelectAllStatus pada awal untuk menyesuaikan status awal
    updateInputs();
    updateSelectAllStatus();
});



    // checkbox button for trip
    $(document).ready(function() {
        function toggleSelectAll() {
            const isChecked = $('#select-all-trip').is(':checked');
            $('.card-item').each(function() {
                const $checkbox = $(this).find('input[name="s_trip[]"]');
                if ($checkbox.length) {
                    $checkbox.prop('checked', isChecked && $(this).is(':visible'));
                }
            });
        }

        function updateSelectAllStatus() {
            const visibleCheckboxes = $('.card-item:visible').find('input[name="s_trip[]"]');
            const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.filter(':checked').length === visibleCheckboxes.length;
            $('#select-all-trip').prop('checked', allChecked);
        }

        $('#select-all-trip').on('change', toggleSelectAll);

        $('input[name="s_trip[]"]').on('change', function() {
            updateSelectAllStatus();
            if ($(this).is(':checked') && $(this).closest('.card-item').is(':hidden')) {
                $(this).prop('checked', false);
            }
        });

        const observer = new MutationObserver(function(mutationsList) {
            mutationsList.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const $cardItem = $(mutation.target);
                    const $checkbox = $cardItem.find('input[name="s_trip[]"]');
                    if ($checkbox.length && $cardItem.is(':hidden')) {
                        $checkbox.prop('checked', false);
                    }
                    updateSelectAllStatus();
                }
            });
        });

        $('.card-item').each(function() {
            observer.observe(this, { attributes: true });
        });

        updateSelectAllStatus();
    });


    // search
    $(document).ready(function() {
        const filters = {
            company: '',
            departure: '',
            arrival: '',
            option: ''
        };

        function updateFilter(key, value) {
            filters[key] = value.toLowerCase();
            applyFilters();
        }

        function applyFilters() {
            const $listItems = $('.card-item');
            let filterActive = false;
            let companyMatched = false; // Tambahkan variabel untuk mengecek apakah company cocok

            $listItems.each(function() {
                const $item = $(this);
                const company = $item.data('company').toLowerCase();
                const departure = $item.data('departure').toLowerCase();
                const arrival = $item.data('arrival').toLowerCase();
                const option = $item.data('option').toLowerCase();

                const matchesCompany = filters.company === '' || company.includes(filters.company);
                const matchesDeparture = filters.departure === '' || departure.includes(filters.departure);
                const matchesArrival = filters.arrival === '' || arrival.includes(filters.arrival);
                const matchesOption = filters.option === '' || option.includes(filters.option);

                if (filters.company || filters.departure || filters.arrival || filters.option) {
                    filterActive = true;
                }

                // Cek apakah ada company yang cocok
                if (matchesCompany) {
                    companyMatched = true;
                }

                $item.toggle(matchesCompany && matchesDeparture && matchesArrival && matchesOption);
            });

            // Menampilkan atau menyembunyikan Shuttle Info dan Select All Trips berdasarkan hasil filter company
            const $shuttleInfoTable = $('#shuttle-info');
            const $selectAllTrips = $('#selectAlltrips');

            if (companyMatched && filterActive) {
                $shuttleInfoTable.show();
                $selectAllTrips.show();
            } else {
                $shuttleInfoTable.hide();
                $selectAllTrips.hide();
            }

            const $noResultsMessage = $('#no-results-message');
            if (filterActive && $listItems.filter(':visible').length === 0) {
                $noResultsMessage.show();
            } else {
                $noResultsMessage.hide();
            }
        }


        $('#search-company, #search-departure, #search-arrival, #search-option').on('input', function() {
            const key = $(this).attr('id').replace('search-', '');
            updateFilter(key, $(this).val());
        });

        $('#search-company, #search-departure, #search-arrival, #search-option').on('change', function() {
            if (!filters.company && !filters.departure && !filters.arrival && !filters.option) {
                $('.card-item').hide();
                $('#shuttle-info').hide();
                $('#selectAlltrips').hide(); // Pastikan elemen checkbox "Select All" juga disembunyikan
            }
        });
    });
</script>

@endsection