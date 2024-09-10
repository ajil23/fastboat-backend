@extends('admin.admin_master')
@section('admin')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <form action="{{ route('currency.updateBulk') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap align-items-center mb-2">
                                        <h5 class="card-title">Edit Currency</h5>
                                    </div>

                                    <div class="row">
                                        @foreach ($currencies as $currency)
                                            <input type="text" class="form-control" id="cy_name_{{ $currency->cy_id }}"
                                                name="cy_name[{{ $currency->cy_id }}]" value="{{ $currency->cy_name }}" hidden>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="cy_rate_{{ $currency->cy_id }}"
                                                        class="form-label">{{ $currency->cy_name }}
                                                        ({{ $currency->cy_code }})
                                                        Rate</label>
                                                    <input type="text" class="form-control"
                                                        id="cy_rate_{{ $currency->cy_id }}"
                                                        name="cy_rate[{{ $currency->cy_id }}]"
                                                        value="{{ number_format($currency->cy_rate, 2, ',', '.') }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col text-end">
                                    <button onclick="history.back()" class="btn btn-outline-dark"><i
                                            class="bx bx-x me-1"></i> Cancel</button>
                                    <button type="submit" class="btn btn-dark"><i class=" bx bx-file me-1"></i>
                                        Update</button>
                                </div> <!-- end col -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
