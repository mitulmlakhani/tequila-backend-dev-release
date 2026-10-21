@extends('layouts.master')
@section('title')
    Payroll Settings
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading">
                        <h4>Payroll Settings</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12">
                    <div class="main-content p-3">
                        <form action="{{ route('restaurant-payroll-settings-save', ['restaurantId' => $restaurantId]) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-4">

                                    <div class="form-group">
                                        <label for="payout_frequency">Payout Frequency <span
                                                class="text-danger">*</span></label>
                                        <select name="payout_frequency" id="payout_frequency"
                                            class="form-control @error('payout_frequency') is-invalid @enderror" required>
                                            <option value="">Select Frequency</option>
                                            <option value="weekly"
                                                {{ isset($config) && $config->payout_frequency == 'weekly' ? 'selected' : '' }}>
                                                Weekly</option>
                                            <option value="monthly"
                                                {{ isset($config) && $config->payout_frequency == 'monthly' ? 'selected' : '' }}>
                                                Monthly</option>
                                        </select>
                                        @error('payout_frequency')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="payout_day">Payout Day ("Monthly: 1-28, Weekly: 1-7, Weekly First day is
                                            Monday.)</label>
                                        <input type="number" name="payout_day" id="payout_day"
                                            class="form-control @error('payout_day') is-invalid @enderror"
                                            value="{{ old('payout_day', $config->payout_day ?? '') }}" min="1"
                                            max="28" placeholder="Monthly: 1-28, Weekly: 1-7">
                                        @error('payout_day')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="payroll_payout_time">Payout Time</label>
                                        <input type="time" name="payroll_payout_time" id="payroll_payout_time"
                                            class="form-control @error('payroll_payout_time') is-invalid @enderror"
                                            value="{{ old('payroll_payout_time', $config->local_payroll_payout_time ?? '00:00') }}">
                                        @error('payroll_payout_time')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group mt-3">
                                        <label for="state_code">State Code <span class="text-danger">*</span></label>
                                        <input type="text" name="state_code" id="state_code"
                                            class="form-control @error('state_code') is-invalid @enderror"
                                            value="{{ old('state_code', $config->state_code ?? '') }}" maxlength="2"
                                            placeholder="e.g., CA, NY" required>
                                        <small class="form-text text-muted">2-letter state code</small>
                                        @error('state_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group mt-3">
                                        <label for="federal_tax_rate">Federal Tax Rate (%)</label>
                                        <input type="text" name="federal_tax_rate" id="federal_tax_rate"
                                            class="form-control @error('federal_tax_rate') is-invalid @enderror"
                                            value="{{ old('federal_tax_rate', $config->federal_tax_rate ?? '') }}"
                                            placeholder="0.00" />
                                        @error('federal_tax_rate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="social_security_employer_rate">Social Security Rate (%)</label>
                                                <input type="text" name="social_security_employer_rate"
                                                    id="social_security_employer_rate"
                                                    class="form-control @error('social_security_employer_rate') is-invalid @enderror"
                                                    value="{{ old('social_security_employer_rate', $config->social_security_employer_rate ?? '6.20') }}"
                                                    placeholder="6.20">

                                                @error('social_security_employer_rate')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="social_security_cap">Social Security Cap</label>
                                                <input type="text" name="social_security_cap" id="social_security_cap"
                                                    class="form-control @error('social_security_cap') is-invalid @enderror"
                                                    value="{{ old('social_security_cap', $config->social_security_cap ?? '184500') }}"
                                                    placeholder="184500">

                                                @error('social_security_cap')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medicare_employer_rate">Medicare Rate (%)</label>
                                        <input type="text" name="medicare_employer_rate" id="medicare_employer_rate"
                                            class="form-control @error('medicare_employer_rate') is-invalid @enderror"
                                            value="{{ old('medicare_employer_rate', $config->medicare_employer_rate ?? '1.45') }}"
                                            placeholder="1.45">

                                        @error('medicare_employer_rate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">FUTA (Federal Unemployment Tax Act)</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="futa_rate">FUTA Rate (%)</label>
                                        <input type="text" name="futa_rate" id="futa_rate"
                                            class="form-control @error('futa_rate') is-invalid @enderror"
                                            value="{{ old('futa_rate', $config->futa_rate ?? '0.0060') }}">

                                        @error('futa_rate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="futa_wage_cap">FUTA Wage Cap</label>
                                        <input type="text" name="futa_wage_cap" id="futa_wage_cap"
                                            class="form-control @error('futa_wage_cap') is-invalid @enderror"
                                            value="{{ old('futa_wage_cap', $config->futa_wage_cap ?? '7000.00') }}"
                                            placeholder="7000.00">

                                        @error('futa_wage_cap')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">SUTA (State Unemployment Tax Act)</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="suta_rate">SUTA Rate (%)</label>
                                        <input type="text" name="suta_rate" id="suta_rate"
                                            class="form-control @error('suta_rate') is-invalid @enderror"
                                            value="{{ old('suta_rate', $config->suta_rate ?? '2.70') }}"
                                            placeholder="2.70">

                                        @error('suta_rate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="suta_wage_cap">SUTA Wage Cap</label>
                                        <input type="text" name="suta_wage_cap" id="suta_wage_cap"
                                            class="form-control @error('suta_wage_cap') is-invalid @enderror"
                                            value="{{ old('suta_wage_cap', $config->suta_wage_cap ?? '9000.00') }}"
                                            placeholder="9000.00">

                                        @error('suta_wage_cap')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            @can('restaurant.storeAddressSettings')
                                <button type="submit" class="btn btn-primary mt-4">Save</button>
                            @endcan
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
