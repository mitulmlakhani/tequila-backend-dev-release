@extends('layouts.master')
@section('title', 'Admin Settings')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-heading">
                    <h4>Admin Settings</h4>
                </div>
            </div>
            @include('layouts.flash-msg')
            {{-- {{ dd($errors) }} --}}
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-content p-3">
                    <form action="{{ route('admin_settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" name="module" value="payment_setting">
                        <div class="section">
                            <h5>Payment Settings</h5>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label" for="cc_fee">Credit Card Fee</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cc_label" name="cc_label" placeholder="Credit Card Fee Setting Label" value="{{ $settings['cc_settings']['label'] ?? 'Credit Card Surcharges' }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cc_fee" name="cc_fee" placeholder="Credit Card Fee" value="{{ $settings['cc_settings']['value'] ?? 4 }}" oninput="validateInput(this)">
                                </div>
                            </div>

                            @if(in_array(auth()->user()->email, config('crypto.btc_config_updater')))
                            <div class="mb-3 row">
                                <input type="hidden" class="form-control" name="cpf_module" value="crypto_setting">
                                <label class="col-md-4 col-form-label" for="cpf_fee">Bitcoin Payment Fees</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cpf_label" name="cpf_label" placeholder="Bitcoin Payment Fee Setting Label" value="{{ $settings['crypto_payment_fees']['label'] ?? 'Bitcoin Payment Fees' }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cpf_fee" name="cpf_fee" placeholder="Bitcoin Payment Fee" value="{{ $settings['crypto_payment_fees']['value'] ?? "" }}" oninput="validateInput(this)">
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <input type="hidden" class="form-control" name="cpa_module" value="crypto_setting">
                                <label class="col-md-4 col-form-label" for="cpa_address">Bitcoin Payment Address</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cpa_label" name="cpa_label" placeholder="Bitcoin Payment Address Setting Label" value="{{ $settings['crypto_payment_address']['label'] ?? 'Bitcoin Payment Address' }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cpa_address" name="cpa_address" placeholder="Bitcoin Payment Address" value="{{ $settings['crypto_payment_address']['value'] ?? "" }}">
                                    @error('cpa_address')
                                        <div class="text-danger validation-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            @endif

                        </div>
                        @can('settings.update')
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function validateInput(input) {
        // Only allow digits and a maximum of one decimal point
        var value = input.value;
        // Regular expression for a valid decimal number with up to two decimal places
        var valid = /^\d*\.?\d{0,2}$/;
        if (!valid.test(value)) {
            input.value = value.slice(0, -1); // Remove last invalid character
        }
    }
</script>