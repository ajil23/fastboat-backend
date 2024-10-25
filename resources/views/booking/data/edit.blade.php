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
                                    <h5 class="mb-1">{{$bookingData->fbo_booking_id}}</h5>
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
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_adult">Adult</label>
                                                    <input id="fbo_adult" name="fbo_adult" type="number"
                                                        class="form-control" value="1" min="1">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_child">Child</label>
                                                    <input id="fbo_child" name="fbo_child" type="number"
                                                        class="form-control" value="0" min="0">
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="mb-3">
                                                    <label class="form-label" for="fbo_infant">Infant</label>
                                                    <input id="fbo_infant" name="fbo_infant" type="number"
                                                        class="form-control" value="0" min="0">
                                                </div>
                                            </div>
                                        </div>
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
                                                    type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_email">Email</label>
                                                <input id="ctc_email" name="ctc_email" placeholder="Enter Email"
                                                    type="email" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_phone">Phone</label>
                                                <input id="ctc_phone" name="ctc_phone" placeholder="Enter Phone"
                                                    type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label class="form-label" for="ctc_nationality">Nationality</label>
                                                <select id="ctc_nationality" class="form-control" name="ctc_nationality">
                                                    <option value="">Select Nationality</option>
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
                                                    type="text" class="form-control">
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
                                                    <option value="">Select Payment Method</option>
                                                    @foreach ($payment as $item)
                                                        <option value="{{ $item->py_value }}"> {{ $item->py_name }}
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
                                                        class="form-control">
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
                                                        class="form-control">
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
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <!-- Cash Transaction ID -->
                                            <div class="transaction-id" id="cash_transaction_id" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cash_transaction_id">Transaction
                                                        ID</label>
                                                    <input id="cash_transaction_id" name="fbo_transaction_id"
                                                        placeholder="Type Recipient" type="text" class="form-control">
                                                </div>
                                            </div>

                                            <!-- Agent Transaction ID -->
                                            <div class="transaction-id" id="agent_transaction_id" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label" for="agent_transaction_id">Agent</label>
                                                    <select id="agent_transaction_id" name="fbo_transaction_id"
                                                        class="form-control">
                                                        <option value="">Select Agent</option>
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
            // Saat payment method diubah
            $('#fbo_payment_method').on('change', function() {
                // Sembunyikan semua Transaction ID
                $('.transaction-id').hide();

                // Dapatkan nilai yang dipilih
                var selectedMethod = $(this).val();

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

                this.submit();
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

                // Create Adult Info
                for (var i = 1; i <= adultCount; i++) {
                    $('#adult_info').append(`
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="adult_name_${i}">Adult ${i} Name</label>
                                    <input id="adult_name_${i}" name="adult_name_[]" type="text" placeholder="Enter Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="adult_age_${i}">Adult ${i} Age</label>
                                    <select id="adult_age_${i}" name="adult_age_[]" class="form-control">
                                        <option value="">Select Age</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="adult_gender_${i}">Adult ${i} Gender</label>
                                    <select id="adult_gender_${i}" name="adult_gender_[]" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="adult_nationality_${i}">Adult ${i} Nationality</label>
                                    <select id="adult_nationality_${i}" name="adult_nationality_[]" class="nationality-select">
                                        <option value="">Select Nationality</option>
                                        @foreach ($nationality as $item)
                                        <option value="{{ $item->nas_country }}">{{ $item->nas_country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    `);
                    var ageSelect = $(`#adult_age_${i}`);
                    for (var age = 13; age <= 49; age++) {
                        ageSelect.append(`<option value="${age}">${age}</option>`);
                    }
                    ageSelect.append('<option value="≧50">≧50</option>');
                }

                // Create Child Info
                for (var i = 1; i <= childCount; i++) {
                    $('#child_info').append(`
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="child_name_${i}">Child ${i} Name</label>
                                    <input id="child_name_${i}" name="child_name_[]" type="text" placeholder="Enter Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="child_age_${i}">Child ${i} Age</label>
                                    <select id="child_age_${i}" name="child_age_[]" class="form-control">
                                        <option value="">Select Age</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="child_gender_${i}">Child ${i} Gender</label>
                                    <select id="child_gender_${i}" name="child_gender_[]" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="child_nationality_${i}">Child ${i} Nationality</label>
                                    <select id="child_nationality_${i}" name="child_nationality_[]" class="nationality-select">
                                        <option value="">Select Nationality</option>
                                        @foreach ($nationality as $item)
                                        <option value="{{ $item->nas_country }}">{{ $item->nas_country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    `);
                    var ageSelect = $(`#child_age_${i}`);
                    for (var age = 3; age <= 12; age++) {
                        ageSelect.append(`<option value="${age}">${age}</option>`);
                    }
                }

                // Create Infant Info
                for (var i = 1; i <= infantCount; i++) {
                    $('#infant_info').append(`
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="infant_name_${i}">Infant ${i} Name</label>
                                    <input id="infant_name_${i}" name="infant_name_[]" type="text" placeholder="Enter Name" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="infant_age_${i}">Infant ${i} Age</label>
                                    <select id="infant_age_${i}" name="infant_age_[]" class="form-control">
                                        <option value="">Select Age</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="infant_gender_${i}">Infant ${i} Gender</label>
                                    <select id="infant_gender_${i}" name="infant_gender_[]" class="form-control">
                                        <option value="">Select Gender</option>
                                        <option value="Female">Female</option>
                                        <option value="Male">Male</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label class="form-label" for="infant_nationality_${i}">Infant ${i} Nationality</label>
                                    <select id="infant_nationality_${i}" name="infant_nationality_[]" class="nationality-select">
                                        <option value="">Select Nationality</option>
                                        @foreach ($nationality as $item)
                                        <option value="{{ $item->nas_country }}">{{ $item->nas_country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    `);
                    var ageSelect = $(`#infant_age_${i}`);
                    for (var age = 0; age <= 2; age++) {
                        ageSelect.append(`<option value="${age}">${age}</option>`);
                    }
                }

                // Kembalikan nilai yang disimpan ke input dan select
                $.each(previousAdultValues, function(id, value) {
                    $('#' + id).val(value);
                });
                $.each(previousChildValues, function(id, value) {
                    $('#' + id).val(value);
                });
                $.each(previousInfantValues, function(id, value) {
                    $('#' + id).val(value);
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
