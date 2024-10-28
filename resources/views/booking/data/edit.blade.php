@extends('admin.admin_master')
@section('admin')
    <style>
        .nav-pills .nav-link.active {
            background-color: #131516 !important;
            color: #fff !important;
        }
    </style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="col-xxl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="position-relative text-center pb-3">
                                <div class="mt-3">
                                    <h5 class="mb-1">{{ $data['fbo_booking_id'] }}</h5>
                                    <p>Trip in <b>{{ $data['fbo_trip_date'] }}</b> from <b>{{ $data['departure_port'] }}
                                            ({{ $data['departure_time'] }})</b> to <b>{{ $data['arrival_port'] }}
                                            ({{ $data['arrival_time'] }})</b> with <b>{{ $data['adult'] }} Adult,
                                            {{ $data['child'] }} Child,
                                            {{ $data['infant'] }} Infant</b></p>
                                </div>
                            </div>
                            <!-- Nav tabs -->
                            <ul class="nav nav-pills nav-justified" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#trip" role="tab">
                                        <span>Trip</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#passenger" role="tab">
                                        <span>Passenger</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#shuttle" role="tab">
                                        <span>Shuttle</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#customer" role="tab">
                                        <span>Customer</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#payment" role="tab">
                                        <span>Payment</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-9">
                    <!-- Tab content -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="trip" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="passenger" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="p-4">
                                        <!-- Placeholder for Adult Information -->
                                        <div id="adult_info"></div>
                                        <!-- Placeholder for Child Information -->
                                        <div id="child_info"></div>
                                        <!-- Placeholder for Infant Information -->
                                        <div id="infant_info"></div>
                                        <!-- Hidden field to store combined passenger info -->
                                        <input type="hidden" id="fbo_passenger" name="fbo_passenger">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="shuttle" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6 shuttle-inputs" id="pickup-inputs">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="fbo_pickup">Pickup Area</label>
                                                            <select style="border-color: lightgray;" class="form-control"
                                                                id="fbo_pickup" name="fbo_pickup">
                                                                <option value="">Select Option</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">Phone</label>
                                                            <input type="text" style="border-color: lightgray;"
                                                                class="form-control" id=""
                                                                name="fbo_contact_pickup" placeholder="62XXXXXXXXXXX">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fbo_specific_pickup">Address
                                                            Pickup</label>
                                                        <textarea id="fbo_specific_pickup" name="fbo_specific_pickup" class="form-control" style="border-color: lightgray;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 shuttle-inputs" id="dropoff-inputs">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="fbo_dropoff">Dropoff
                                                                Area</label>
                                                            <select style="border-color: lightgray;" class="form-control"
                                                                id="fbo_dropoff" name="fbo_dropoff">
                                                                <option value="">Select Option</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label" for="">Phone</label>
                                                            <input type="text" style="border-color: lightgray;"
                                                                class="form-control" id=""
                                                                name="fbo_contact_dropoff" placeholder="62XXXXXXXXXXX">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="fbo_specific_dropoff">Address
                                                            Dropoff</label>
                                                        <textarea id="fbo_specific_dropoff" name="fbo_specific_dropoff" class="form-control"
                                                            style="border-color: lightgray;"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>

                        <div class="tab-pane" id="customer" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_name">Name</label>
                                                <input id="ctc_name" name="ctc_name" placeholder="Enter Name"
                                                    type="text" class="form-control" value="{{ $data['name'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_email">Email</label>
                                                <input id="ctc_email" name="ctc_email" placeholder="Enter Email"
                                                    type="email" class="form-control" value="{{ $data['email'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_phone">Phone</label>
                                                <input id="ctc_phone" name="ctc_phone" placeholder="Enter Phone"
                                                    type="text" class="form-control" value="{{ $data['phone'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_nationality">Nationality</label>
                                                <select id="ctc_nationality" class="form-control" name="ctc_nationality">
                                                    <option value="{{ $data['nationality_id'] }}">
                                                        {{ $data['nationality_name'] }}</option>
                                                    @foreach ($nationality as $item)
                                                        <option value="{{ $item->nas_id }}">{{ $item->nas_country }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_phone">Note</label>
                                                <input id="ctc_phone" name="ctc_phone" placeholder="Enter Note"
                                                    type="text" class="form-control" value="{{ $data['note'] }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                        </div>


                        <div class="tab-pane" id="payment" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-6">
                                                <label for="fbo_payment_method" class="form-label">Payment Method</label>
                                                <select class="form-control" id="fbo_payment_method"
                                                    name="fbo_payment_method" required>
                                                    <option value="{{ $data['paymentMethod_value'] }}">
                                                        {{ $data['paymentMethod_name'] }}</option>
                                                    @foreach ($payment as $item)
                                                        <option value="{{ $item->py_value }}">{{ $item->py_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="transaction-id" id="paypal_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="paypal_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="paypal_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Paypal Transaction ID" type="text"
                                                        class="form-control" value="{{ $data['paymentMethod_value'] === 'paypal' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Midtrans Transaction ID -->
                                            <div class="transaction-id" id="midtrans_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="midtrans_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="midtrans_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Midtrans Transaction ID" type="text"
                                                        class="form-control" value="{{ $data['paymentMethod_value'] === 'midtrans' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Bank Transfer Transaction ID -->
                                            <div class="transaction-id" id="bank_transfer_transaction_id"
                                                style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="bank_transfer_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="bank_transfer_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Bank Transaction ID" type="text"
                                                        class="form-control" value="{{ $data['paymentMethod_value'] === 'bank_transfer' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Cash Transaction ID -->
                                            <div class="transaction-id" id="cash_transaction_id" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cash_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="cash_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Recipient" type="text" class="form-control" value="{{ $data['paymentMethod_value'] === 'cash' ? $data['transaction_id'] : '' }}">
                                                </div>
                                            </div>

                                            <!-- Agent Transaction ID -->
                                            <div class="transaction-id" id="agent_transaction_id" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="agent_transaction_id">Agent</label>
                                                    <select id="agent_transaction_id" name="fbo_transaction_id"
                                                        class="form-control">
                                                        <option value="{{ $data['paymentMethod_value'] === 'agent' ? $data['transaction_id'] : '' }}">{{ $data['paymentMethod_value'] === 'agent' ? $data['transaction_id'] : '' }}</option>
                                                        <option value="Agen A">Agen A</option>
                                                        <option value="Agen B">Agen B</option>
                                                        <option value="Agen C">Agen C</option>
                                                        <option value="Agen D">Agen D</option>
                                                        <option value="Agen E">Agen E</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col text-end">
                            <button type="button" onclick="history.back()" class="btn btn-outline-dark"><i
                                    class="bx bx-x me-1"></i> Cancel</button>
                            <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i> Update </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        @include('admin.components.footer')
    </div>
@endsection
@section('script')
    <script>
        // Payment
        $(document).ready(function() {
            // Fungsi untuk menampilkan Transaction ID berdasarkan pilihan
            function showTransactionField(selectedMethod) {
                // Sembunyikan semua Transaction ID
                $('.transaction-id').hide();

                // Tampilkan Transaction ID berdasarkan pilihan
                if (selectedMethod === 'paypal') {
                    $('#paypal_transaction_id').show();
                } else if (selectedMethod === 'midtrans') {
                    $('#midtrans_transaction_id').show();
                } else if (selectedMethod === 'bank_transfer') {
                    $('#bank_transfer_transaction_id').show();
                } else if (selectedMethod === 'cash') {
                    $('#cash_transaction_id').show();
                } else if (selectedMethod === 'agent') {
                    $('#agent_transaction_id').show();
                }
            }

            // Panggil fungsi showTransactionField saat halaman dimuat berdasarkan nilai default
            showTransactionField($('#fbo_payment_method').val());

            // Saat payment method diubah
            $('#fbo_payment_method').on('change', function() {
                showTransactionField($(this).val());
            });

            // Saat form disubmit
            $('form').submit(function(e) {
                e.preventDefault(); // Mencegah submit form secara default
                var isValid = false; // Flag untuk mengecek validasi

                // Nonaktifkan semua input yang tidak terlihat (tersembunyi)
                $('.transaction-id').each(function() {
                    if ($(this).is(':hidden')) {
                        // Disable input atau select pada elemen yang disembunyikan
                        $(this).find('input, select').prop('disabled', true);
                    } else {
                        // Enable input atau select pada elemen yang terlihat
                        $(this).find('input, select').prop('disabled', false);
                        isValid = true; // Validasi berhasil jika ada input yang terlihat
                    }
                });

                if (isValid) this.submit(); // Submit form hanya jika ada input yang terlihat
            });

            function updateInfo() {
                // Simpan nilai yang sudah diisi sebelumnya
                var previousAdultValues = {};
                $('#adult_info input, #adult_info select').each(function() {
                    previousAdultValues[$(this).attr('id')] = $(this).val();
                });

                var previousChildValues = {};
                $('#child_info input, #child_info select').each(function() {
                    previousChildValues[$(this).attr('id')] = $(this).val();
                });

                var previousInfantValues = {};
                $('#infant_info input, #infant_info select').each(function() {
                    previousInfantValues[$(this).attr('id')] = $(this).val();
                });

                // Clear all current info
                $('#adult_info, #child_info, #infant_info').empty();

                // Get values of each count
                var adultCount = parseInt($('#fbo_adult').val()) || 1;
                var childCount = parseInt($('#fbo_child').val()) || 0;
                var infantCount = parseInt($('#fbo_infant').val()) || 0;
                var passengers = @json($data['passengers']);

                passengers.forEach((passenger, index) => {
                    let passengerIndex = index + 1;
                    let passengerType = passenger.age < 3 ? 'infant' : (passenger.age <= 12 ? 'child' :
                        'adult');

                    // Append info berdasarkan tipe penumpang
                    $(`#${passengerType}_info`).append(`
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="${passengerType}_name_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Name</label>
                                    <input id="${passengerType}_name_${passengerIndex}" name="${passengerType}_name_[]" type="text" value="${passenger.name}" placeholder="Enter Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="${passengerType}_age_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Age</label>
                                    <select id="${passengerType}_age_${passengerIndex}" name="${passengerType}_age_[]" class="form-control">
                                        <option value="">Select Age</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="${passengerType}_gender_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Gender</label>
                                    <select id="${passengerType}_gender_${passengerIndex}" name="${passengerType}_gender_[]" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Female" ${passenger.gender === 'Female' ? 'selected' : ''}>Female</option>
                                        <option value="Male" ${passenger.gender === 'Male' ? 'selected' : ''}>Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="${passengerType}_nationality_${passengerIndex}">${passengerType.charAt(0).toUpperCase() + passengerType.slice(1)} ${passengerIndex} Nationality</label>
                                    <select id="${passengerType}_nationality_${passengerIndex}" name="${passengerType}_nationality_[]" class="nationality-select">
                                        <option value="">Select Nationality</option>
                                        @foreach ($nationality as $item)
                                            <option value="{{ $item->nas_country }}" ${passenger.nationality === "{{ $item->nas_country }}" ? 'selected' : ''}>{{ $item->nas_country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    `);

                    // Populate age options based on type
                    let ageSelect = $(`#${passengerType}_age_${passengerIndex}`);
                    let ageRange = passengerType === 'infant' ? [0, 2] : (passengerType === 'child' ? [3,
                        12
                    ] : [13, 49]);
                    for (let age = ageRange[0]; age <= ageRange[1]; age++) {
                        ageSelect.append(
                            `<option value="${age}" ${passenger.age == age ? 'selected' : ''}>${age}</option>`
                        );
                    }
                    if (passengerType === 'adult') {
                        ageSelect.append(
                            '<option value="≧50" ${passenger.age == "≧50" ? "selected" : ""}>≧50</option>'
                        );
                    }
                });

                // Simpan data ke fbo_passenger
                savePassengerInfo();

                // Initialize Tom Select on all newly created select elements with class 'nationality-select'
                $('.nationality-select').each(function() {
                    new TomSelect(this);
                });

                // Attach event listeners to update fbo_passenger when inputs change
                attachInputListeners();
            }

            function savePassengerInfo() {
                var passengerInfo = [];

                // Get Adult Info
                $('#adult_info .row').each(function() {
                    var name = $(this).find('[name="adult_name_[]"]').val();
                    var age = $(this).find('[name="adult_age_[]"]').val();
                    var gender = $(this).find('[name="adult_gender_[]"]').val();
                    var nationality = $(this).find('[name="adult_nationality_[]"]').val();

                    passengerInfo.push(`${name},${age},${gender},${nationality}`);
                });

                // Get Child Info
                $('#child_info .row').each(function() {
                    var name = $(this).find('[name="child_name_[]"]').val();
                    var age = $(this).find('[name="child_age_[]"]').val();
                    var gender = $(this).find('[name="child_gender_[]"]').val();
                    var nationality = $(this).find('[name="child_nationality_[]"]').val();

                    passengerInfo.push(`${name},${age},${gender},${nationality}`);
                });

                // Get Infant Info
                $('#infant_info .row').each(function() {
                    var name = $(this).find('[name="infant_name_[]"]').val();
                    var age = $(this).find('[name="infant_age_[]"]').val();
                    var gender = $(this).find('[name="infant_gender_[]"]').val();
                    var nationality = $(this).find('[name="infant_nationality_[]"]').val();

                    passengerInfo.push(`${name},${age},${gender},${nationality}`);
                });

                // Gabungkan semua informasi penumpang dengan tanda ;
                var combinedInfo = passengerInfo.join(';');

                // Set nilai ke field fbo_passenger
                $('#fbo_passenger').val(combinedInfo);
            }

            function attachInputListeners() {
                $('#adult_info input, #adult_info select').on('input change', function() {
                    savePassengerInfo();
                });

                $('#child_info input, #child_info select').on('input change', function() {
                    savePassengerInfo();
                });

                $('#infant_info input, #infant_info select').on('input change', function() {
                    savePassengerInfo();
                });
            }

            // Call updateInfo function when the DOM is ready
            updateInfo();

            // Trigger updateInfo when the adult, child, or infant count changes
            $('#fbo_adult, #fbo_child, #fbo_infant').on('change', function() {
                updateInfo();
            });
        });
    </script>
@endsection
