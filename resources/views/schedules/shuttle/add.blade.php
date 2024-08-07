@extends('admin.admin_master')
@section('admin')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <form action="{{ route('shuttle.search') }}" method="post" enctype="multipart/form-data">
                @csrf
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
            </form>
            <div id="results" class="row mt-2">
                @foreach ($trip as $item)
                    <div class="col-xl-4 col-sm-6 card-item" data-company="{{ $item->schedule->company->cpn_name }}" data-departure="{{ $item->departure->prt_name_en }}" data-arrival="{{ $item->arrival->prt_name_en }}" data-option="{{ $item->fbt_shuttle_option }}" data-trip="{{ $item->fbt_id }}" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input trip-checkbox" type="checkbox" id="formCheck{{ $item->fbt_id }}">
                                    <label class="form-check-label" for="formCheck{{ $item->fbt_id }}">
                                        {{ $item->schedule->company->cpn_name }}
                                    </label>
                                </div>
                                <div class="mt-3 pt-1">
                                    <p>From : {{ $item->departure->prt_name_en }}, {{ $item->departure->island->isd_name }} ({{ date('H:i', strtotime($item->fbt_dept_time)) }})</p>
                                    <p>To : {{ $item->arrival->prt_name_en }}, {{ $item->arrival->island->isd_name }} ({{ date('H:i', strtotime($item->fbt_arrival_time)) }})</p>
                                    <p hidden>Option : {{ $item->fbt_shuttle_option }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div id="shuttle-info" class="col-lg-12" style="display: none;">
                    <form action="{{ route('shuttle.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
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
                                                        <input type="checkbox" class="checkedbox" id="s_id" onclick="toggleSelectAll(this)">
                                                    </div>
                                                </th>
                                                <th><center>Area</center></th>
                                                <th><center>Start</center></th>
                                                <th><center>End</center></th>
                                                <th><center>Meeting Point</center></th>
                                                <th><center>Note</center></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($area as $item)
                                                <tr>
                                                    <th scope="row" class="ps-4">
                                                        <div class="form-check font-size-16">
                                                            <input type="checkbox" class="checkedbox" name="selected_ids[]" value="{{ $item->s_id }}" onclick="updateSelectAllState(); updateButtonState()">
                                                        </div>
                                                    </th>
                                                    <td>
                                                        <center>
                                                            <input type="text" class="form-control" value="{{ $item->sa_name }}" readonly>
                                                            <input type="hidden" name="s_area[]" value="{{ $item->sa_name }}">
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <input id="s_start" name="s_start[]" type="time" class="form-control">
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <input id="s_end" name="s_end[]" type="time" class="form-control">
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <div class="form-check form-switch" style="display: flex; align-items: center;justify-content: center;">
                                                                <input class="form-check-input" style="width: 3rem; height: 1.75rem; border-radius: 1rem;" type="checkbox" id="switch" name="meeting_point_switch[]">
                                                            </div>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <input id="s_meeting_point" name="s_meeting_point[]" placeholder="Note/Meeting Point Location" type="text" class="form-control" disabled>
                                                        </center>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <input type="hidden" name="s_trip" id="s_trip">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col text-end">
                                <button onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                                <button type="submit" class="btn btn-dark"><i class="bx bx-file me-1"></i> Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('admin.components.footer')
    </div>
</div>
@endsection

@section('script')

<script>
    new TomSelect("#search-company");
    new TomSelect("#search-departure");
    new TomSelect("#search-arrival");
    new TomSelect("#search-option");

    document.addEventListener('DOMContentLoaded', function() {
    const switchElements = document.querySelectorAll('input[name="meeting_point_switch[]"]');
    const textInputs = document.querySelectorAll('input[name="s_meeting_point[]"]');

    switchElements.forEach((switchElement, index) => {
        switchElement.addEventListener('change', function() {
            textInputs[index].disabled = !switchElement.checked;
        });
    });

    const tripCheckboxes = document.querySelectorAll('.trip-checkbox');
    tripCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', function() {
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
        });
    });
});

function toggleSelectAll(checkbox) {
    const isChecked = checkbox.checked;
    document.querySelectorAll('input[name="selected_ids[]"]').forEach(function (cb) {
        cb.checked = isChecked;
    });
    updateButtonState();
}

function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('s_id');
    const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
    let allChecked = true;

    checkboxes.forEach(function (checkbox) {
        if (!checkbox.checked) {
            allChecked = false;
        }
    });

    selectAllCheckbox.checked = allChecked;
}

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
