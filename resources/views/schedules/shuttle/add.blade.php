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
                                                <label class="form-label" for="s_trip">Company</label>
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
                                                <label class="form-label" for="s_area">Departure Port</label>
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
                                                <label class="form-label" for="s_meeting_point">Arrival Port</label>
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
                                                <label class="form-label" for="s_start">Option</label>
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
                    <div id="select-all-container" style="display: none;"> <!-- Sembunyikan checkbox ini pada awal -->
                        <input type="checkbox" id="select-all-trip"> Pilih Semua Trip
                    </div>
                    @foreach ($trip as $item)
                    <div class="col-xl-4 col-sm-6 card-item" data-company="{{$item->schedule->company->cpn_name}}" data-departure="{{$item->departure->prt_name_en}}" data-arrival="{{$item->arrival->prt_name_en}}" data-option="{{$item->fbt_shuttle_option}}" style="display: none;"> <!-- Sembunyikan card secara default -->
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="formCheck1" value="{{$item->fbt_id}}" name="s_trip[]">
                                    <label class="form-check-label" for="formCheck1">
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
                                                        <input type="checkbox" class="checkedbox" id="select-all">
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
                                                        <input type="checkbox" class="checkedbox" name="selected_ids[]" data-row="{{ $index }}">
                                                    </div>
                                                </th>
                                                <td>
                                                    <center>
                                                        <input type="text" class="form-control" value="{{$item->sa_name}}" readonly>
                                                        <input type="hidden" id="input" class="form-control" value="{{$item->sa_id}}" name="s_area[]">
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <input type="time" class="form-control" name="s_start[]" id="input">
                                                    </center>
                                                </td>
                                                <td>
                                                    <center>
                                                        <input type="time" class="form-control" name="s_end[]" id="input">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk mengaktifkan atau menonaktifkan input berdasarkan status checkbox
        function updateInputs() {
            document.querySelectorAll('input[name="selected_ids[]"]').forEach(function(checkbox) {
                // Temukan baris terkait dengan checkbox
                const rowIndex = checkbox.getAttribute('data-row');
                const inputs = document.querySelectorAll(`tr:nth-child(${parseInt(rowIndex) + 1}) #input`);

                // Aktifkan atau nonaktifkan input berdasarkan status checkbox
                inputs.forEach(function(input) {
                    input.disabled = !checkbox.checked;
                });
            });
        }

        // Fungsi untuk memilih atau membatalkan semua checkbox
        function toggleSelectAll() {
            const isChecked = document.querySelector('#select-all').checked;
            document.querySelectorAll('input[name="selected_ids[]"]').forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
            updateInputs();
        }

        // Fungsi untuk mengatur status checkbox "Pilih Semua"
        function updateSelectAllStatus() {
            const allChecked = document.querySelectorAll('input[name="selected_ids[]"]:checked').length ===
                document.querySelectorAll('input[name="selected_ids[]"]').length;
            document.querySelector('#select-all').checked = allChecked;
        }

        // Tambahkan event listener untuk setiap checkbox
        document.querySelectorAll('input[name="selected_ids[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                updateInputs();
                updateSelectAllStatus(); // Update status "Pilih Semua" setiap kali checkbox diubah
            });
        });

        // Tambahkan event listener untuk checkbox 'Pilih Semua'
        document.querySelector('#select-all').addEventListener('change', toggleSelectAll);

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
    document.addEventListener('DOMContentLoaded', function() {
        const switchElements = document.querySelectorAll('input[name="meeting_point_switch[]"]');
        const textInputs = document.querySelectorAll('input[name="s_meeting_point[]"]');

        switchElements.forEach((switchElement, index) => {
            switchElement.addEventListener('change', function() {
                textInputs[index].disabled = !switchElement.checked;
            });
        });

        const tripCheckboxes = document.querySelectorAll('.trip-checkbox');
        const selectAllCheckbox = document.getElementById('select-all-trip'); // Checkbox "Pilih Semua"

        function updateSelectedTrips() {
            const selectedTrips = [];
            tripCheckboxes.forEach((cb) => {
                if (cb.checked) {
                    selectedTrips.push(cb.closest('.card-item').getAttribute('data-trip'));
                }
            });

            if (selectedTrips.length > 0) {
                document.getElementById('s_trip').value = selectedTrips.join(',');
                document.getElementById('shuttle-info').style.display = '';
            } else {
                document.getElementById('s_trip').value = '';
                document.getElementById('shuttle-info').style.display = 'none';
            }

            // Update status "Pilih Semua" checkbox
            selectAllCheckbox.checked = selectedTrips.length === tripCheckboxes.length;
        }

        tripCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', updateSelectedTrips);
        });

        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = selectAllCheckbox.checked;
            tripCheckboxes.forEach((cb) => {
                cb.checked = isChecked;
            });
            updateSelectedTrips();
        });

    });

    // search
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
        const listItems = document.querySelectorAll('.card-item');
        let filterActive = false;

        listItems.forEach((item) => {
            const company = item.getAttribute('data-company').toLowerCase();
            const departure = item.getAttribute('data-departure').toLowerCase();
            const arrival = item.getAttribute('data-arrival').toLowerCase();
            const option = item.getAttribute('data-option').toLowerCase();

            const matchesCompany = filters.company === '' || company.includes(filters.company);
            const matchesDeparture = filters.departure === '' || departure.includes(filters.departure);
            const matchesArrival = filters.arrival === '' || arrival.includes(filters.arrival);
            const matchesOption = filters.option === '' || option.includes(filters.option);

            if (filters.company || filters.departure || filters.arrival || filters.option) {
                filterActive = true;
            }

            item.style.display = (matchesCompany && matchesDeparture && matchesArrival && matchesOption) ? '' : 'none';
        });

        const shuttleInfoTable = document.getElementById('shuttle-info');
        shuttleInfoTable.style.display = filterActive ? '' : 'none';

        const noResultsMessage = document.getElementById('no-results-message');
        if (filterActive && document.querySelectorAll('.card-item[style="display: none;"]').length === listItems.length) {
            noResultsMessage.style.display = '';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }

    document.querySelector('#search-company').addEventListener('input', (e) => updateFilter('company', e.target.value));
    document.querySelector('#search-departure').addEventListener('input', (e) => updateFilter('departure', e.target.value));
    document.querySelector('#search-arrival').addEventListener('input', (e) => updateFilter('arrival', e.target.value));
    document.querySelector('#search-option').addEventListener('input', (e) => updateFilter('option', e.target.value));

    document.querySelectorAll('#search-company, #search-departure, #search-arrival, #search-option').forEach((element) => {
        element.addEventListener('change', function() {
            if (!filters.company && !filters.departure && !filters.arrival && !filters.option) {
                document.querySelectorAll('.card-item').forEach(item => item.style.display = 'none');
                document.getElementById('shuttle-info').style.display = 'none';
            }
        });
    });
</script>

@endsection