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
                                                        <option value="{{$item->cpn_name}}">{{$item->cpn_name}}</option>
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
                                                        <option value="{{$item->departure->prt_name_en}}">{{$item->departure->prt_name_en}}</option>
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
                                                        <option value="{{$item->arrival->prt_name_en}}">{{$item->arrival->prt_name_en}}</option>
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
                                                        <option value="{{$item->fbt_shuttle_option}}">{{$item->fbt_shuttle_option}}</option>
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
                        @foreach ($trip as $item)
                        <div class="col-xl-4 col-sm-6 card-item" data-company="{{$item->schedule->company->cpn_name}}" data-departure="{{$item->departure->prt_name_en}}" data-arrival="{{$item->arrival->prt_name_en}}" data-option="{{$item->fbt_shuttle_option}}" style="display: none;"> <!-- Sembunyikan card secara default -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="formCheck1" value="{{$item->fbt_id}}" name="s_trip">
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
                                                            <input type="checkbox" class="checkedbox" id="sa_id" onclick="toggleSelectAll(this)"> 
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
                                                @foreach ($area as $item)
                                                <tr>
                                                    <th scope="row" class="ps-4">
                                                        <div class="form-check font-size-16">
                                                            <input type="checkbox" class="checkedbox" name="selected_ids[]" onclick="updateSelectAllState(); updateButtonState()">
                                                        </div>
                                                    </th>
                                                    <td>
                                                        <center>
                                                            <input type="text" class="form-control" value="{{$item->sa_name}}" name="s_area" readonly>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <input type="time" class="form-control" name="s_start">
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <input type="time" class="form-control" name="s_end">
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <div class="form-check form-switch" style="display: flex; align-items: center;justify-content: center;">
                                                                <input class="form-check-input" style="width: 3rem; height: 1.75rem; border-radius: 1rem;" type="checkbox" id="switch" />
                                                            </div>
                                                        </center>
                                                    </td>
                                                    <td>
                                                        <center>
                                                            <input id="s_meeting_point" name="s_meeting_point" placeholder="Note/Meeting Point Location" type="text" class="form-control"></input>
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
                            <button onclick="history.back()" class="btn btn-outline-dark"><i class="bx bx-x me-1"></i> Cancel</button>
                            <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Save</button>
                        </div> <!-- end col -->
                    </div> <!-- end row-->
                    <!-- end row -->
        
                </form>
            
        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    @include('admin.components.footer')
</div>
@endsection

@section('script')

{{-- tom select --}}
<script>
    new TomSelect("#search-company");
    new TomSelect("#search-departure");
    new TomSelect("#search-arrival");
    new TomSelect("#search-option");
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen checkbox dan input teks
        const switchElement = document.getElementById('switch');
        const textInput = document.getElementById('s_meeting_point');

        // Fungsi untuk memperbarui status input berdasarkan switch
        function updateInputStatus() {
            textInput.disabled = !switchElement.checked;
        }

        // Tambahkan event listener untuk switch
        switchElement.addEventListener('change', updateInputStatus);

        // Inisialisasi status input pada saat halaman dimuat
        updateInputStatus();
    });

    function toggleSelectAll(checkbox) {
        const isChecked = checkbox.checked;
        document.querySelectorAll('input[name="selected_ids[]"]').forEach(function (cb) {
            cb.checked = isChecked;
        });
        updateButtonState();
    }

    function updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('sa_id');
        const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
        let allChecked = true;

        checkboxes.forEach(function (checkbox) {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        selectAllCheckbox.checked = allChecked;
    }

    // Objek untuk menyimpan nilai filter dari setiap dropdown
    const filters = {
        company: '',
        departure: '',
        arrival: '',
        option: ''
    };

    // Fungsi untuk memperbarui filter berdasarkan inputan
    function updateFilter(key, value) {
        filters[key] = value.toLowerCase();
        applyFilters();
    }

    // Fungsi untuk menerapkan semua filter pada list item
    function applyFilters() {
        const listItems = document.querySelectorAll('.card-item');

        // Variabel untuk memeriksa apakah ada filter aktif
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

            // Cek jika ada filter aktif
            if (filters.company || filters.departure || filters.arrival || filters.option) {
                filterActive = true;
            }

            item.style.display = (matchesCompany && matchesDeparture && matchesArrival && matchesOption) ? '' : 'none';
        });

        // Tampilkan atau sembunyikan tabel shuttle info
        const shuttleInfoTable = document.getElementById('shuttle-info');
        shuttleInfoTable.style.display = filterActive ? '' : 'none';

        // Tampilkan pesan tidak ada hasil jika semua card tersembunyi
        const noResultsMessage = document.getElementById('no-results-message');
        if (filterActive && document.querySelectorAll('.card-item[style="display: none;"]').length === listItems.length) {
            noResultsMessage.style.display = '';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }

    // Event listeners untuk input pencarian
    document.querySelector('#search-company').addEventListener('input', (e) => updateFilter('company', e.target.value));
    document.querySelector('#search-departure').addEventListener('input', (e) => updateFilter('departure', e.target.value));
    document.querySelector('#search-arrival').addEventListener('input', (e) => updateFilter('arrival', e.target.value));
    document.querySelector('#search-option').addEventListener('input', (e) => updateFilter('option', e.target.value));

    // Event listeners untuk menghapus dropdown
    document.querySelectorAll('#search-company, #search-departure, #search-arrival, #search-option').forEach((element) => {
        element.addEventListener('change', function() {
            // Jika dropdown kosong, sembunyikan card dan shuttle info
            if (!filters.company && !filters.departure && !filters.arrival && !filters.option) {
                document.querySelectorAll('.card-item').forEach(item => item.style.display = 'none');
                document.getElementById('shuttle-info').style.display = 'none';
            }
        });
    });
</script>



@endsection