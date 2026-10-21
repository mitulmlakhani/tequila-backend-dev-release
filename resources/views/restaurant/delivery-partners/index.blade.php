@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">
@endsection

@extends('layouts.master') @section('content')
    <style></style>
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">

                <div class="col-12 col-md-12 col-lg-12">
                    <div class="row">
                        <div class="main-heading">
                            <div class="col-10 col-md-10 col-lg-10">
                                <h4>{{ trans('lang.delivery') }} {{ trans('lang.partners') }}</h4>
                            </div>
                            <div class="col-2 col-md-2 col-lg-2">
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">

                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <h4>UberEats</h4>
                                @if($ubereats['is_enabled'] ?? 0)

                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <form class="form" action="{{ route('dp.update-ubereats-store-status') }}"
                                            method="post">
                                            @csrf
                                            <input type="hidden" name="store_id" value="{{ $ubereats['store_id'] ?? '' }}">
                                            <br>
                                            @if($ubereats['status'] ?? 0)
                                                <input type="hidden" name="status" value="0">
                                                <h5 class="text-success">Store is <strong>ONLINE</strong> now.</h5> <br />
                                                <div class="mb-3">
                                                    <label for="offline_till" class="form-label">Offline Till</label>
                                                    <input type="text" id="offline_till" name="offline_till" required
                                                        class="form-control datepicker">
                                                </div>

                                                <div class="mb-4">
                                                    <label for="reason" class="form-label">Reason</label>
                                                    <select class="form-select" name="reason" aria-label="reason" required>
                                                        <option value="" selected>Select Reason</option>
                                                        @foreach ($ubereats['cancel_reasons'] as $reason)
                                                            <option value="{{ $reason }}">{{ $reason }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <button class="btn btn-danger">Stop Receiving Order</button>

                                            @else
                                                <input type="hidden" name="status" value="1">
                                                <p class="text-danger">Store is closed till
                                                    <strong>{{ $ubereats['offline_until'] ?? "" }}</strong> due to
                                                    <strong>{{ $ubereats['offline_reason'] ?? "" }}</strong>.</p> <br />

                                                <button class="btn btn-success">Start Receiving Order</button>
                                            @endif

                                        </form>
                                    </div>

                                    <div class="col-md-4 col-lg-4 col-sm-12">
                                        <form class="form" id="auto_order_acceptance_form" action="{{ route('dp.ubereats-update-auto-accept') }}" method="post">
                                            @csrf
                                            <br>
                                            <h5></h5>
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="auto_order_acceptance" name="auto_order_acceptance" type="checkbox"
                                                        value="1" {{ ($ubereats['config']['auto_accept'] ?? null) ? 'checked' : '' }} id="auto_order_acceptance">
                                                    <label class="form-check-label ms-2 h5" for="auto_order_acceptance">
                                                        Auto Order Accept
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="auto_order_prep_time" class="form-label">Auto Order Preparation Time (In Minutes)</label>
                                                <input type="number" id="auto_order_prep_time" value="{{ $ubereats['config']['auto_order_prep_time'] ?? null }}" name="auto_order_prep_time" class="form-control">
                                                @error('auto_order_prep_time')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="auto_order_handler_id" class="form-label">Auto Order Handle By</label>
                                                <select class="form-select" name="auto_order_handler_id" id="auto_order_handler_id">
                                                    <option value="">Select Employee</option>
                                                    @foreach ($employees as $employee)
                                                        <option {{ ($ubereats['config']['auto_order_handler_id'] ?? null) == $employee->id ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('auto_order_handler_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <button class="btn btn-success">Save</button>
                                        </form>
                                    </div>

                                    <div class="col-md-4 col-lg-4 col-sm-12 d-flex flex-column align-items-center justify-content-center mx-auto px-5 text-center">
                                        <a class="btn btn-danger mb-3" onclick="return confirm('Are you sure you want to sync the menu with UberEats? This will remove your existing menu sync and replace it with the TequilaPOS menu on UberEats.')">Sync Menu with UberEats Now</a>
                                        <span class="text-danger text-help">This will remove your existing menu sync TequilaPOS menu on UberEats.</span>
                                    </div>

                                @else
                                    <h6 class="mt-2 text-danger">
                                    Your UberEats integration is disabled. Please contact support to enable it.
                                    </h6>
                                @endif

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection

@section('js')
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>
    <script>
        flatpickr(".datepicker", {
            enableTime: true,
            dateFormat: "m-d-Y H:i",
            allowInput: true
        });
    </script>
@endsection