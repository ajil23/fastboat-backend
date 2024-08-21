@extends('admin.admin_master')
@section('admin')
<style>
    #custom-calendar {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    grid-template-rows: repeat(6, auto);
    gap: 2px;
}

#custom-calendar div {
    border: 1px solid #ddd;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 5px;
    cursor: pointer;
    height: 120px;
}

#custom-calendar .header {
    background-color: #f5f5f5;
    font-weight: bold;
    height: auto;
}

/* Colors for header days only */
#custom-calendar .header:nth-child(1) {
    background-color: #f9dcdc; /* Red background for Sunday */
    color: red; /* Red text for Sunday */
}

#custom-calendar .header:nth-child(6) {
    background-color: #d9f9dc; /* Green background for Friday */
    color: green; /* Green text for Friday */
}

#custom-calendar .today,
#custom-calendar .selected {
    background-color: transparent;
    color: inherit;
}

#custom-calendar table {
    width: 100%;
    margin-top: 5px;
}

#custom-calendar table td {
    padding: 2px;
    text-align: center;
}

#custom-calendar table input[type="checkbox"] {
    margin: 0;
}
</style>
<div class="main-content">
    <div class="page-content">
        <div id="addproduct-accordion" class="custom-accordion">
            <div class="row">
                <div class="ms-auto">
                    <div class="btn-toolbar float-end" role="toolbar">
                        <div class="btn-group me-2 mb-2">
                            <a href="{{route('availability.extend')}}" class="btn btn-outline-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i>Extend</a>
                        </div>
                        <div class="btn-group me-2 mb-2">
                            <a href="{{route('availability.add')}}" class="btn btn-dark w-100" id="btn-new-event"><i class="mdi mdi-plus"></i>Add</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <a href="#addproduct-productinfo-collapse" class="text-body" data-bs-toggle="collapse" aria-expanded="true" aria-controls="addproduct-productinfo-collapse">
                    <div class="p-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="font-size-16 mb-1">Fast Boat Availability</h5>
                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                            </div>
                        </div>
                    </div>
                </a>

                <div id="addproduct-productinfo-collapse" class="collapse show" data-bs-parent="#addproduct-accordion">
                    <div class="p-4 border-top">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Company</label>
                                    <select name="fba_company" id="search-company">
                                        <option value="">Select Company</option>
                                        @foreach ($company as $item)
                                        <option value="{{ $item->cpn_name }}">{{ $item->cpn_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Fast Boat</label>
                                    <select name="fba_fastboat" id="search-fastboat">
                                        <option value="">Select Fast Boat</option>
                                        @foreach ($fastboat as $item)
                                        <option value="{{ $item->fb_name }}">{{ $item->fb_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div><div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Schedule</label>
                                    <select name="fba_schedule" id="search-schedule">
                                        <option value="">Select Schedule</option>
                                        @foreach ($schedule as $item)
                                        <option value="{{ $item->sch_name }}">{{ $item->sch_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Route</label>
                                    <select name="fba_route" id="search-route">
                                        <option value="">Select Route</option>
                                        @foreach ($route as $item)
                                        <option value="{{ $item->rt_dept_island }} to {{ $item->rt_arrival_island }}">{{ $item->rt_dept_island }} to {{ $item->rt_arrival_island }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Date range</label>
                                    <input type="text" class="form-control flatpickr-input" id="daterange" placeholder="Input date range">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Departure</label>
                                    <select name="fba_departure" id="search-departure">
                                        <option value="">Select Departure Port</option>
                                        @foreach ($departure as $item)
                                        <option value="{{ $item->prt_name_en}}">{{ $item->prt_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Arrival</label>
                                    <select name="fba_arrival" id="search-arrival">
                                        <option value="">Select Arrival Port</option>
                                        @foreach ($arrival as $item)
                                        <option value="{{ $item->prt_name_en}}">{{ $item->prt_name_en}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label">Dept time</label>
                                    <select name="fba_dept_time" id="search-dept_time">
                                        <option value="">Select Dept time</option>
                                        @foreach ($deptTime as $item)
                                        <option value="{{date('H:i', strtotime($item->fbt_dept_time));}}">{{date('H:i', strtotime($item->fbt_dept_time));}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-12">
                            <h5 class="font-size-14 mb-3">Update Type </h5>
                        </div>
                        <div class="row">
                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="price">
                                    <label for="price">Price</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="stock">
                                    <label for="stock">Stock</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="pax">
                                    <label for="pax">Min Pax</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="shuttle-status">
                                    <label for="shuttle-status">Shuttle Status</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="available-status">
                                    <label for="available-status">Available Status</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="availability-info">
                                    <label for="availability-info">Availability Info</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="custom-time">
                                    <label for="custom-time">Custom Dept & Arriv Time</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-6">
                                <div class="form-check font-size-16">
                                    <input type="checkbox" class="form-check-input type" id="all-type">
                                    <label for="all-type">Select All Type</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="position-relative text-center border-bottom pb-3">
                        <button class="btn  btn-outline-dark"><i class="mdi mdi-pencil"></i>&thinsp;Update</button>
                    </div>
                    <div class="card-body">
                        <div class="col-xl-3 col-lg-6">
                            <div class="form-check font-size-16">
                                <input type="checkbox" class="form-check-input" id="all-trips">
                                <label for="all-trips">All Trips</label>
                            </div>
                        </div>
                    </div>
                    <div id="custom-calendar" class="mb-4">
                        <div class="header">SUN</div>
                        <div class="header">MON</div>
                        <div class="header">TUE</div>
                        <div class="header">WED</div>
                        <div class="header">THU</div>
                        <div class="header">FRI</div>
                        <div class="header">SAT</div>
                    </div>
                </div>
            </div>
        </div>
               
    </div>
    <!-- End Page-content -->
    @include('admin.components.footer')
</div>
@endsection
@section('script')
<script>
    new TomSelect("#search-company");
    new TomSelect("#search-fastboat");
    new TomSelect("#search-schedule");
    new TomSelect("#search-departure");
    new TomSelect("#search-arrival");
    new TomSelect("#search-route");
    new TomSelect("#search-dept_time");

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
    
    // all check update type
    $(document).ready(function() {
        const $allTypeCheckbox = $('#all-type');
        const $checkboxes = $('.type').not('#all-type');

        // Fungsi untuk mengaktifkan/menonaktifkan semua checkbox
        function toggleAllCheckboxes(state) {
            $checkboxes.prop('checked', state);
        }

        // Event listener untuk checkbox "all-type"
        $allTypeCheckbox.on('change', function() {
            toggleAllCheckboxes($(this).is(':checked'));
        });

        // Event listeners untuk semua checkbox lainnya
        $checkboxes.on('change', function() {
            const allChecked = $checkboxes.length === $checkboxes.filter(':checked').length;
            $allTypeCheckbox.prop('checked', allChecked);
        });
    });

    // Add custom calendar script here
    document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('custom-calendar');
    let selectedDate = null;

    // Ensure availability data is available
    const availability = @json($availability);
    console.log(availability);

    // Find the earliest and latest date in the availability data
    const earliestDate = availability.length > 0 ? new Date(Math.min(...availability.map(a => new Date(a.fba_date)))) : new Date();
    const latestDate = availability.length > 0 ? new Date(Math.max(...availability.map(a => new Date(a.fba_date)))) : new Date();

    // Calculate the starting date for the calendar, which is 2 days before the earliest date
    earliestDate.setDate(earliestDate.getDate() - 2);

    function renderCalendar(startDate, endDate) {
        calendar.innerHTML = '';

        const daysOfWeek = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
        daysOfWeek.forEach(day => {
            const dayHeader = document.createElement('div');
            dayHeader.classList.add('header');
            dayHeader.textContent = day;
            calendar.appendChild(dayHeader);
        });

        let currentDate = new Date(startDate);
        const totalDays = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

        // Add empty cells for days before the start date
        for (let i = 0; i < currentDate.getDay(); i++) {
            const dayCell = document.createElement('div');
            calendar.appendChild(dayCell);
        }

        // Add days from startDate to endDate
        for (let i = 0; i < totalDays; i++) {
            const dayCell = document.createElement('div');
            dayCell.textContent = currentDate.getDate();

            if (currentDate.toDateString() === new Date().toDateString()) {
                dayCell.classList.add('today');
            }

            if (selectedDate && currentDate.toDateString() === selectedDate.toDateString()) {
                dayCell.classList.add('selected');
            }

            // Add a red outline for the last day of the month
            const lastDateInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            if (currentDate.getDate() === lastDateInMonth.getDate()) {
                dayCell.style.outline = '2px solid red';
            }

            const table = document.createElement('table');
            const tbody = document.createElement('tbody');

            // Filter and show availability for the current day
            const dailyAvailability = availability.filter(a => new Date(a.fba_date).toDateString() === currentDate.toDateString());

            dailyAvailability.forEach((data, index) => {
                const row = document.createElement('tr');
                const cellCheckbox = document.createElement('td');
                const cellData = document.createElement('td');

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = `checkbox-${currentDate.getFullYear()}-${currentDate.getMonth()}-${currentDate.getDate()}-${index + 1}`;
                checkbox.id = `checkbox-${currentDate.getFullYear()}-${currentDate.getMonth()}-${currentDate.getDate()}-${index + 1}`;
                checkbox.checked = false; // Adjust if needed

                cellCheckbox.appendChild(checkbox);
                cellData.textContent = `Trip ID: ${data.fba_trip_id}`; // Customize based on your data

                row.appendChild(cellCheckbox);
                row.appendChild(cellData);
                tbody.appendChild(row);
            });

            table.appendChild(tbody);
            dayCell.appendChild(table);

            dayCell.addEventListener('click', function(event) {
                if (event.target.tagName !== 'INPUT') {
                    selectedDate = new Date(currentDate);
                    renderCalendar(startDate, endDate);
                }
            });

            calendar.appendChild(dayCell);

            // Move to the next day
            currentDate.setDate(currentDate.getDate() + 1);
        }
    }

    // Render calendar starting from the calculated startDate until the latest date in availability data
    renderCalendar(earliestDate, latestDate);
});

</script>
@endsection