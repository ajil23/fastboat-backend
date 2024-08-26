@extends('admin.admin_master')
@section('admin')
<style>
    /* Style untuk kalender */
    .calendar-table {
        table-layout: fixed;
        width: 100%;
        border-spacing: 0 5px;
        
    /* Add spacing between rows */
    }

    .calendar-table th,
    .calendar-table td {
        width: 14.28%;
        vertical-align: top;
        padding: 10px;
        position: relative;
    }

    .calendar-table th {
        font-size: 0.9rem;
        height: 40px;
        text-align: center;
    }

    .calendar-table td {
        padding: 10px;
        font-size: 0.85rem;
        line-height: 1.2;
    }

    .calendar-table .calendar-date {
    font-weight: bold;
    display: flex;
    justify-content: center; /* Ratakan konten di tengah secara horizontal */
    align-items: center; /* Ratakan konten di tengah secara vertikal */
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.calendar-date input[type="checkbox"] {
    margin-right: 5px;
}

    .company-name {
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 5px;
        font-size: 0.8rem;
    }

    .schedule-name {
        margin-left: 10px;
        margin-bottom: 3px;
        font-size: 0.85rem;
    }

    .availability-entry {
        margin-top: 10px;
        margin-bottom: 5px;
        font-size: 0.8rem;
    }

    .availability-entry input[type="checkbox"] {
        margin-right: 5px;
    }

    .border-danger {
        border: 2px solid red !important;
    }

    .calendar-table th.sunday {
        background-color: #f8d7da;
        color: #dc3545;
    }

    .calendar-table th.friday {
        background-color: #d4edda;
        color: #28a745;
        height: 40px;
    }

    .calendar-table td {
        padding: 15px;
        /* Increased padding for more space in data cells */
    }

    .calendar-table .calendar-date {
        position: absolute;
        top: 5px;
        right: 5px;
        font-weight: bold;
    }

    .calendar-entry {
        display: flex;
        align-items: center;
        margin-top: 20px;
    }

    .calendar-entry input[type="checkbox"] {
        margin-right: 5px;
    }

    .border-danger {
        border: 2px solid red !important;
        /* Ensure the red border is visible */
    }

    .calendar-table th.sunday {
        background-color: #f8d7da;
        /* Latar belakang merah muda untuk hari Minggu */
        color: #dc3545;
        /* Teks merah untuk hari Minggu */
    }

    .calendar-table th.friday {
        background-color: #d4edda;
        /* Latar belakang hijau muda untuk hari Jumat */
        color: #28a745;
        /* Teks hijau untuk hari Jumat */
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
                            </div>
                            <div class="col-lg-3">
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
                </div>
            </div>
        </div>
       
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="font-size-14 mb-3">Kalender Ketersediaan</h5>
                        @php
                            // Mendapatkan tanggal awal dan akhir dari data availability
                            $firstDate = \Carbon\Carbon::parse($availability->first()->fba_date)->startOfMonth();
                            $lastDate = \Carbon\Carbon::parse($availability->last()->fba_date)->endOfMonth();

                            // Mendapatkan hari pertama dalam bulan ini (0 untuk Minggu, 6 untuk Sabtu)
                            $startDayOfWeek = $firstDate->dayOfWeek;
                            $currentDate = $firstDate->copy();

                            // Hitung total minggu yang diperlukan dalam kalender
                            $totalWeeks = ceil(($lastDate->day + $startDayOfWeek) / 7);

                            // Mengelompokkan data availability berdasarkan tanggal
                            $availabilityByDate = $availability->groupBy(function ($item) {
                                return \Carbon\Carbon::parse($item->fba_date)->format('Y-m-d');
                            });
                        @endphp
                        <table class="table table-bordered calendar-table">
                            <thead>
                                <tr>
                                    <th class="text-center sunday">SUN</th>
                                    <th class="text-center">MON</th>
                                    <th class="text-center">TUE</th>
                                    <th class="text-center">WED</th>
                                    <th class="text-center">THU</th>
                                    <th class="text-center friday">FRI</th>
                                    <th class="text-center">SAT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($week = 0; $week < $totalWeeks; $week++)
                                    <tr>
                                        @for ($day = 0; $day < 7; $day++)
                                            @if ($week === 0 && $day < $startDayOfWeek)
                                                <!-- Kosongkan sel sebelum tanggal pertama -->
                                                <td></td>
                                            @elseif ($currentDate->gt($lastDate))
                                                <!-- Kosongkan sel setelah tanggal terakhir -->
                                                <td></td>
                                            @else
                                                @php
                                                    $dateString = $currentDate->format('Y-m-d');
                                                @endphp

                                                @if ($availabilityByDate->has($dateString))
                                                    <td class="{{ $currentDate->isSunday() ? 'sunday' : ($currentDate->isFriday() ? 'friday' : '') }}">
                                                        <!-- Tampilkan Tanggal dengan Checkbox -->
                                                        <div class="calendar-date">
                                                            <input type="checkbox" name="select_date[]" value="{{ $dateString }}" />
                                                            <span>{{ $currentDate->format('d M Y') }}</span>
                                                        </div>

                                                        <!-- Looping untuk setiap perusahaan dan schedule -->
                                                        @foreach ($availabilityByDate[$dateString]->groupBy('trip.fastboat.company.cpn_name') as $companyName => $companyData)
                                                            @foreach ($companyData->groupBy('trip.schedule.sch_name') as $scheduleName => $scheduleData)
                                                                <div class="company-name">{{ $companyName }} / {{ $scheduleName }}</div>

                                                                <!-- Looping untuk setiap availability dalam schedule -->
                                                                @foreach ($scheduleData as $item)
                                                                    <div class="availability-entry">
                                                                        <input type="checkbox" name="select_availability[]" value="{{ $item->id }}" />
                                                                        <span>{{ $item->trip->departure->island->isd_code }}-{{ $item->trip->arrival->island->isd_code }} 
                                                                        {{ \Carbon\Carbon::parse($item->fba_dept_time)->format('H:i') }} 
                                                                        ({{ $item->fba_stock }})</span>
                                                                    </div>
                                                                @endforeach
                                                            @endforeach
                                                        @endforeach
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif

                                                @php
                                                    $currentDate->addDay();
                                                @endphp
                                            @endif
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
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

    // Fungsi untuk memperbarui status checkbox 'all-trips'
    function updateAllTripsCheckbox() {
        const checkboxes = document.querySelectorAll('input.form-check-input[name="item[]"]');
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
        document.getElementById('all-trips').checked = allChecked;
    }

    // Event listener untuk checkbox 'all-trips'
    document.getElementById('all-trips').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('input.form-check-input[name="item[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        }, this);
    });

    // Event listener untuk semua checkbox dengan name 'item[]'
    document.querySelectorAll('input.form-check-input[name="item[]"]').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateAllTripsCheckbox();
        });
    });
</script>
@endsection