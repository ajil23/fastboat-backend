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
                                            <h5 class="font-size-16 mb-1">Trip Search</h5>
                                            <p class="text-muted text-truncate mb-0">Search the trip</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <div class="collapse show" data-bs-parent="#addproduct-accordion">
                                <div class="p-4 border-top">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="mb-3">
                                                <label class="form-label">Fast Boat</label>
                                                <select name="fbt_fastboat" id="search-fastboat">
                                                    <option value="">Select Fast Boat</option>
                                                    @foreach ($trip as $item)
                                                    <option value="{{ $item->fastboat->fb_name }}">{{ $item->fastboat->fb_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
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
                                        <div class="col-lg-4">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{route('availability.store')}}" method="POST">
                @csrf
                <div id="results" class="row mt-2">
                    <div class="form-check font-size-16" id="selectAlltrips" style="display: none;">
                        <input type="checkbox" class="form-check-input" id="select-all-trip">
                        <label for="select-all-trip">Select All</label>
                        <p class="text-danger font-size-12">*It's better to check one by one to reduce mismatches in adding trip availability</p>
                    </div>
                    @foreach ($trip as $item)
                    <div class="col-xl-4 col-sm-6 card-item" data-fastboat="{{$item->fastboat->fb_name}}" data-departure="{{$item->departure->prt_name_en}}" data-arrival="{{$item->arrival->prt_name_en}}" data-option="{{$item->fbt_shuttle_option}}" style="display: none;"> <!-- Sembunyikan card secara default -->
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="{{$item->fbt_id}}" name="fba_trip_id[]" id="trip + {{$item->fbt_id}}">
                                    <label class="form-check-label" for="trip + {{$item->fbt_id}}">
                                        {{$item->fastboat->fb_name}}
                                    </label>
                                </div>
                                <div class="mt-3 pt-1">
                                    <p>From : {{$item->departure->prt_name_en}}, {{$item->departure->island->isd_name}} ({{date('H:i', strtotime($item->fbt_dept_time))}})</p>
                                    <p>To : {{$item->arrival->prt_name_en}}, {{$item->arrival->island->isd_name}} ({{date('H:i', strtotime($item->fbt_arrival_time))}})</p>
                                    <p>Avb : until </p>
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
                                                <h5 class="font-size-16 mb-1"> Availability Extand</h5>
                                                <p class="text-danger text-truncat font-size-12">*All price are in Indonesian Rupiah</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                                    
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
        </div>
        </form>
    </div>
    @include('admin.components.footer')
</div>
</div>
@endsection

@section('script')
<script>
    // mengatur tampilan pencarian
    new TomSelect("#search-fastboat");
    new TomSelect("#search-departure");
    new TomSelect("#search-arrival");


    // checkbox button for trip
    $(document).ready(function() {
        function toggleSelectAll() {
            const isChecked = $('#select-all-trip').is(':checked');
            $('.card-item').each(function() {
                const $checkbox = $(this).find('input[name="fba_trip_id[]"]');
                if ($checkbox.length) {
                    $checkbox.prop('checked', isChecked && $(this).is(':visible'));
                }
            });
        }

        function updateSelectAllStatus() {
            const visibleCheckboxes = $('.card-item:visible').find('input[name="fba_trip_id[]"]');
            const allChecked = visibleCheckboxes.length > 0 && visibleCheckboxes.filter(':checked').length === visibleCheckboxes.length;
            $('#select-all-trip').prop('checked', allChecked);
        }

        $('#select-all-trip').on('change', toggleSelectAll);

        $('input[name="fba_trip_id[]"]').on('change', function() {
            updateSelectAllStatus();
            if ($(this).is(':checked') && $(this).closest('.card-item').is(':hidden')) {
                $(this).prop('checked', false);
            }
        });

        const observer = new MutationObserver(function(mutationsList) {
            mutationsList.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const $cardItem = $(mutation.target);
                    const $checkbox = $cardItem.find('input[name="fba_trip_id[]"]');
                    if ($checkbox.length && $cardItem.is(':hidden')) {
                        $checkbox.prop('checked', false);
                    }
                    updateSelectAllStatus();
                }
            });
        });

        $('.card-item').each(function() {
            observer.observe(this, {
                attributes: true
            });
        });

        updateSelectAllStatus();
    });


    // search
    $(document).ready(function() {
        const filters = {
            fastboat: '',
            departure: '',
            arrival: ''
        };

        function updateFilter(key, value) {
            filters[key] = value.toLowerCase();
            applyFilters();
        }

        function applyFilters() {
            const $listItems = $('.card-item');
            let filterActive = false;
            let fastboatMatched = false;

            $listItems.each(function() {
                const $item = $(this);
                const fastboat = $item.data('fastboat').toLowerCase();
                const departure = $item.data('departure').toLowerCase();
                const arrival = $item.data('arrival').toLowerCase();

                const matchesFastboat = !filters.fastboat || fastboat.includes(filters.fastboat);
                const matchesDeparture = !filters.departure || departure.includes(filters.departure);
                const matchesArrival = !filters.arrival || arrival.includes(filters.arrival);

                const shouldShow = matchesFastboat && matchesDeparture && matchesArrival;

                if (shouldShow) {
                    fastboatMatched = true;
                }

                $item.toggle(shouldShow);
            });

            const $shuttleInfoTable = $('#shuttle-info');
            const $selectAllTrips = $('#selectAlltrips');

            if (fastboatMatched) {
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

        $('#search-fastboat, #search-departure, #search-arrival').on('input', function() {
            const key = $(this).attr('id').replace('search-', '');
            updateFilter(key, $(this).val());
        });

        $('#search-fastboat, #search-departure, #search-arrival').on('change', function() {
            if (!filters.fastboat && !filters.departure && !filters.arrival) {
                $('.card-item').hide();
                $('#shuttle-info').hide();
                $('#selectAlltrips').hide();
            }
        });

        updateSelectAllStatus();
    });
</script>

<script>
    flatpickr("#daterange", {
        mode: "range",
        minDate: "today",
        dateFormat: "d-m-Y",
        disable: [
            function(date) {
                return !(date.getDate() % 100);
            }
        ]
    });
</script>
@endsection