@extends('layouts.master')
@section('title')
@if (empty($restaurant))
Add
@else
Edit
@endif Restaurant
@endsection
@section('css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
@endsection

@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>
                            @if (empty($restaurant))
                                Add
                            @else
                                Edit
                            @endif 
                            @if(!($restaurant->parent_id ?? null)) Restaurant @else Branch @endif
                        </h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <form method="POST" id="restaurant_add">
                            @csrf
                        @if(!empty($restaurant))
                            <input type="hidden" name="is_edit" value="1">
                        @endif
                        <div class="row">
                            @if(!($restaurant->parent_id ?? null))
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="name">Restaurant Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Restaurant Name" id="name" name="name"
                                    class="form-control" value="{{old('name', !empty($restaurant) ? $restaurant->name : '')}}" required maxlength="50">
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @else
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="branch_name">Location Name <span class="text-danger">*</span></label>
                                <input type="text" placeholder="Location Name" id="branch_name" name="branch_name"
                                    class="form-control" value="{{old('branch_name', $restaurant->branch_name ?? '')}}" required maxlength="50">
                                @error('branch_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="phone">Official Phone <span class="text-danger">*</span></label>
                                <input type="text" placeholder="(787) 898-9898" id="phone" name="phone"
                                    class="form-control max10Length" value="{{old('phone', !empty($restaurant) ? $restaurant->phone : '')}}" required minlength="14" maxlength="14" onkeyup="formatPhone(this)">
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="open_time">Opening Time <span class="text-danger">*</span></label>
                                <input type="time" id="open_time" name="open_time"
                                    class="form-control" value="{{old('open_time', !empty($restaurant) ? ($restaurant->open_time ? \Carbon\Carbon::parse($restaurant->open_time)->format('H:i') : '') : '')}}" required autocomplete="off">
                                @error('open_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="close_time">Closing Time <span class="text-danger">*</span></label>
                                <input type="time" id="close_time" name="close_time"
                                    class="form-control" value="{{old('close_time', !empty($restaurant) ? ($restaurant->close_time ? \Carbon\Carbon::parse($restaurant->close_time)->format('H:i') : '') : '')}}" required autocomplete="off">
                                @error('close_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="first_weekday">First WeekDay <span class="text-danger">*</span></label>
                                @php
                                    $selectedFirstWeekday = (string) old('first_weekday', $restaurant->first_weekday ?? '0');
                                @endphp
                                <select class="form-select" id="first_weekday" name="first_weekday" required>
                                    <option {{ $selectedFirstWeekday === '0' ? 'selected' : '' }} value="0">Sunday</option>
                                    <option {{ $selectedFirstWeekday === '1' ? 'selected' : '' }} value="1">Monday</option>
                                    <option {{ $selectedFirstWeekday === '2' ? 'selected' : '' }} value="2">Tuesday</option>
                                    <option {{ $selectedFirstWeekday === '3' ? 'selected' : '' }} value="3">Wednesday</option>
                                    <option {{ $selectedFirstWeekday === '4' ? 'selected' : '' }} value="4">Thursday</option>
                                    <option {{ $selectedFirstWeekday === '5' ? 'selected' : '' }} value="5">Friday</option>
                                    <option {{ $selectedFirstWeekday === '6' ? 'selected' : '' }} value="6">Saturday</option>
                                </select>
                                @error('first_weekday')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="dine_in_status">Dine In Status</label>
                                <div class="form-check form-switch">
                                    @php
                                        $dineInChecked = session()->hasOldInput()
                                            ? (bool) old('dine_in_status')
                                            : (!empty($restaurant) && ($restaurant->dine_in_status == 'Yes' || $restaurant->dine_in_status == '1'));
                                    @endphp
                                    <input class="form-check-input" type="checkbox" role="switch" id="dine_in_status"
                                        name="dine_in_status" value="1" {{ $dineInChecked ? 'checked' : '' }}>
                                @error('dine_in_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="take_away_status">Take Away Status</label>
                                <div class="form-check form-switch">
                                    @php
                                        $takeAwayChecked = session()->hasOldInput()
                                            ? (bool) old('take_away_status')
                                            : (!empty($restaurant) && ($restaurant->take_away_status == 'Yes' || $restaurant->take_away_status == '1'));
                                    @endphp
                                    <input class="form-check-input" type="checkbox" role="switch" id="take_away_status"
                                        name="take_away_status" value="1" {{ $takeAwayChecked ? 'checked' : '' }}>
                                    @error('take_away_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="delivery_status">Delivery Status</label>
                                <div class="form-check form-switch">
                                    @php
                                        $deliveryChecked = session()->hasOldInput()
                                            ? (bool) old('delivery_status')
                                            : (!empty($restaurant) && ($restaurant->delivery_status == 'Yes' || $restaurant->delivery_status == '1'));
                                    @endphp
                                    <input class="form-check-input" type="checkbox" role="switch" id="delivery_status"
                                        name="delivery_status" value="1" {{ $deliveryChecked ? 'checked' : '' }}>
                                    @error('delivery_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="address">Address <span class="text-danger">*</span></label>
                                <textarea rows="3" placeholder="Restaurant Address" id="address"
                                        name="address" class="form-control" required>{{old('address', !empty($restaurant) ? $restaurant->address : '')}}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-3 col-lg-3">
                                <label class="form-label" for="timezone">Timezone <span class="text-danger">*</span></label>
                                @php
                                    $selectedTimezone = old('timezone', $restaurant->timezone ?? 'America/Chicago');
                                @endphp
                                <select class="form-select" id="timezone" name="timezone" required>
                                    <option value="">Select Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone->name }}" {{ $selectedTimezone == $timezone->name ? 'selected' : '' }}>
                                            {{ $timezone->title}} ({{ $timezone->abbreviation }}) {{ $timezone->offset }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-3 col-md-3 col-lg-3">
                                <label class="form-label" for="status">Restaurant Status</label>
                                @php
                                    $selectedStatus = old('status', !empty($restaurant) ? $restaurant->status : 'Active');
                                @endphp
                                <select class="form-select" id="status" name="status">
                                    <option value="Active" {{ $selectedStatus == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="In-active" {{ $selectedStatus == 'In-active' ? 'selected' : '' }}>In-active</option>
                                </select>
                                @error('status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(!($restaurant->parent_id ?? null))
                            @php
                                $isMultiBranchChecked = session()->hasOldInput()
                                    ? (bool) old('is_multi_branch')
                                    : (!empty($restaurant) && (bool) $restaurant->is_multi_branch);
                            @endphp
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_multi_branch" name="is_multi_branch" {{ $isMultiBranchChecked ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_multi_branch">
                                        Is Multi Location
                                    </label>
                                </div>
                                <div id="max_branch_limit_container" style="{{ $isMultiBranchChecked ? 'display:block;' : 'display:none;' }}">
                                    <label class="form-label" for="max_branch_limit">Maximum Location Limit <span class="text-danger">*</span></label>
                                    <input type="number" id="max_branch_limit" name="max_branch_limit" min="1" class="form-control numberInput" placeholder="Location Limit" value="{{old('max_branch_limit', !empty($restaurant) ? $restaurant->max_branch_limit : '')}}" {{ $isMultiBranchChecked ? 'required' : '' }}>
                                    @error('max_branch_limit')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="website_name">Website Name (Unique sub domain name) <span class="text-danger">*</span></label>
                                <input type="text" id="website_name" name="website_name" class="form-control" placeholder="unique sub domain name" value="{{ old('website_name', !empty($restaurant) ? $restaurant->website_name : '') }}" required maxlength="50">
                                @error('website_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <label class="" for=""><b>Credit card charge detail</b></label>
                            <hr>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="cc_fee_label">Credit Card Fee label <span class="text-danger">*</span></label>
                                <input type="text" id="cc_fee_label" name="cc_fee_label" class="form-control" placeholder="Credit Card Fee label" value="{{old('cc_fee_label', !empty($restaurant) ? $restaurant->cc_fee_label : '')}}" required>
                                @error('cc_fee_label')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="cc_fee">Credit Card Fee (in %) <span class="text-danger">*</span></label>
                                <input type="text" id="cc_fee" name="cc_fee" class="form-control percentageInput" placeholder="Credit Card Fee" value="{{old('cc_fee', !empty($restaurant) ? $restaurant->cc_fee : '')}}" required>
                                @error('cc_fee')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="cd_label">Cash Discount label <span class="text-danger">*</span></label>
                                <input type="text" id="cd_label" name="cd_label" class="form-control" placeholder="Cash Discount label" value="{{old('cd_label', !empty($restaurant) ? $restaurant->cd_label : '')}}" required>
                                @error('cd_label')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                @php
                                    $isCcFeeApplicableChecked = session()->hasOldInput()
                                        ? (bool) old('is_cc_fee_applicable')
                                        : (!empty($restaurant) && (bool) $restaurant->is_cc_fee_applicable);
                                    $isCcFeeShowChecked = session()->hasOldInput()
                                        ? (bool) old('is_cc_fee_show')
                                        : (!empty($restaurant) && (bool) $restaurant->is_cc_fee_show);
                                @endphp
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_cc_fee_applicable" name="is_cc_fee_applicable" {{ $isCcFeeApplicableChecked ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_cc_fee_applicable">
                                        Is Credit Fee Applicable
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_cc_fee_show" name="is_cc_fee_show" {{ $isCcFeeShowChecked ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_cc_fee_show">
                                        Is Credit Fee Show
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Dual Pricing Setting -->
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label class="form-label" for="dual_pricing">Dual Pricing Display</label>
                                    @php
                                        $selectedDualPricing = old('dual_pricing', $dual_pricing ?? 'inter_change');
                                    @endphp
                                    <select name="dual_pricing" id="dual_pricing" class="form-select">
                                        <option value="inter_change" {{ $selectedDualPricing == 'inter_change' ? 'selected' : '' }}>Inter Change Price</option>
                                        <option value="cash_discount" {{ $selectedDualPricing == 'cash_discount' ? 'selected' : '' }}>Cash Discount Price</option>
                                        <option value="dual_price" {{ $selectedDualPricing == 'dual_price' ? 'selected' : '' }}>Dual Price (Cash & Card)</option>
                                    </select>
                                    <small class="text-muted">If enabled, the receipt will display both cash and credit card prices.</small>
                                </div>
                            </div>

                            @if (empty($restaurant))
                            <label class="" for=""><b>User detail</b></label>
                            <hr>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="user_name">Name <span class="text-danger">*</span></label>
                                    <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Name" value="{{old('user_name')}}" required>
                                    @error('user_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="email">Official Email <span class="text-danger">*</span></label>
                                    <input type="email" placeholder="example@domain.com" id="email" name="email"
                                        class="form-control" value="{{old('email')}}" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" minlength="8" id="password" name="password" class="form-control" placeholder="Password (min 8 characters)"
                                        required autocomplete="new-password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="c_password">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" minlength="8" id="c_password" name="c_password"
                                        class="form-control" placeholder="Confirm Password" required>
                                    <div id="c_password_error" class="text-danger d-none mt-1">Confirm password must match password.</div>
                                    @error('c_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            
                            @if(in_array(auth()->user()->email, config('crypto.btc_config_updater')))
                                <label class="mt-5" for=""><b>Crypto Payment detail</b></label>
                                <hr>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="bitcoin_payment_address">Bitcoin Payment Address</label>
                                    <input type="text" id="bitcoin_payment_address" name="bitcoin_payment_address" class="form-control" placeholder="Bitcoin Payment Address" value="{{old('bitcoin_payment_address', !empty($restaurant) ? $restaurant->bitcoin_payment_address : '')}}">
                                    @error('bitcoin_payment_address')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="bitcoin_payment_fees">Bitcoin Payment Fees (%)</label>
                                    <input type="text" id="bitcoin_payment_fees" name="bitcoin_payment_fees" class="form-control percentageInput" placeholder="Bitcoin Payment Fees (%)" value="{{ old('bitcoin_payment_fees', !empty($restaurant->bitcoin_payment_fees ?? null) ? $restaurant->bitcoin_payment_fees : \App\Models\AdminSetting::where('key', 'crypto_payment_fees')->value('value')) }}">
                                    @error('bitcoin_payment_fees')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <label class="mt-5" for=""><b>Payment Methods <span class="text-danger">*</span></b></label>
                            <hr>
                            <div id="payment_methods_error" class="text-danger mb-2 @if(!$errors->has('restaurant_payment_methods')) d-none @endif">
                                @if($errors->has('restaurant_payment_methods'))
                                    {{ $errors->first('restaurant_payment_methods') }}
                                @else
                                    Please select at least one payment method.
                                @endif
                            </div>
                            <div class="row">
                                @foreach ($paymentMethods as $method)
                                    @php
                                        $methodIsActiveInDb = isset($restaurantPaymentMethods[$method->id]) && $restaurantPaymentMethods[$method->id] == '1';
                                        $methodChecked = session()->hasOldInput()
                                            ? in_array((string)$method->id, array_map('strval', (array) old('restaurant_payment_methods', [])))
                                            : $methodIsActiveInDb;
                                    @endphp
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                            <span class="fw-medium">{{ $method->name }}</span>
                                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                                <input type="checkbox"
                                                    class="form-check-input status-switch-toggle"
                                                    name="restaurant_payment_methods[]"
                                                    id="toggle_{{ $method->id }}"
                                                    value="{{ $method->id }}"
                                                    {{ $methodChecked ? 'checked' : '' }}>
                                                <label class="form-check-label small text-muted switch-status-label" for="toggle_{{ $method->id }}">
                                                    {{ $methodChecked ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <label class="mt-5" for=""><b>Outside Payment Methods</b></label>
                            <hr>
                            <div class="row">
                                @php
                                    $outsideCcChecked = session()->hasOldInput()
                                        ? (bool) old('outside_cc_payments')
                                        : (bool) ($settings['outside_cc_payments'] ?? false);
                                    $ubereatsChecked = session()->hasOldInput()
                                        ? (bool) old('ubereats')
                                        : (bool) ($settings['ubereats'] ?? false);
                                    $doordashChecked = session()->hasOldInput()
                                        ? (bool) old('doordash')
                                        : (bool) ($settings['doordash'] ?? false);
                                    $grubhubChecked = session()->hasOldInput()
                                        ? (bool) old('grubhub')
                                        : (bool) ($settings['grubhub'] ?? false);
                                @endphp
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Outside Credit Card Payments</span>
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input type="checkbox"
                                                class="form-check-input status-switch-toggle"
                                                name="outside_cc_payments"
                                                id="outside_cc_payments"
                                                {{ $outsideCcChecked ? 'checked' : '' }}
                                                value="1">
                                            <label class="form-check-label small text-muted switch-status-label" for="outside_cc_payments">
                                                {{ $outsideCcChecked ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">UberEats</span>
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input type="checkbox"
                                                class="form-check-input status-switch-toggle"
                                                name="ubereats"
                                                id="ubereats"
                                                {{ $ubereatsChecked ? 'checked' : '' }}
                                                value="1">
                                            <label class="form-check-label small text-muted switch-status-label" for="ubereats">
                                                {{ $ubereatsChecked ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">DoorDash</span>
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input type="checkbox"
                                                class="form-check-input status-switch-toggle"
                                                name="doordash"
                                                id="doordash"
                                                {{ $doordashChecked ? 'checked' : '' }}
                                                value="1">
                                            <label class="form-check-label small text-muted switch-status-label" for="doordash">
                                                {{ $doordashChecked ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>  
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                        <span class="fw-medium">Grubhub</span>
                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                            <input type="checkbox"
                                                class="form-check-input status-switch-toggle"
                                                name="grubhub"
                                                id="grubhub"
                                                {{ $grubhubChecked ? 'checked' : '' }}
                                                value="1">
                                            <label class="form-check-label small text-muted switch-status-label" for="grubhub">
                                                {{ $grubhubChecked ? 'Active' : 'Inactive' }}
                                            </label>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                @foreach ($deliveryPartners as $key => $dp)
                                    @php
                                        $dpPartner = $dp['delivery_partner'];
                                        $dpOld = old("delivery_partners.{$key}", []);
                                        $dpStatus = session()->hasOldInput()
                                            ? !empty($dpOld['status'])
                                            : (bool) ($dp['status'] ?? false);
                                        $dpMerchantId = session()->hasOldInput()
                                            ? ($dpOld['merchant_id'] ?? '')
                                            : ($dp['merchant_id'] ?? '');
                                        $dpConfig = session()->hasOldInput()
                                            ? ($dpOld['config'] ?? [])
                                            : ($dp['config'] ?? []);
                                    @endphp
                                    @switch($dpPartner)
                                        @case('ubereats')
                                            <input type="hidden" name="delivery_partners[{{ $key }}][delivery_partner]" value="{{ $dpPartner }}">
                                            <input type="hidden" name="delivery_partners[{{ $key }}][id]" value="{{ $dp['id'] }}">
                                            <div class="col-4">
                                                <label class="mt-5" for=""><b>UberEats</b></label>
                                                <hr>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                                            <span class="fw-medium">Status</span>
                                                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input dp-status-toggle status-switch-toggle"
                                                                    name="delivery_partners[{{ $key }}][status]"
                                                                    id="toggle_{{ $dpPartner }}"
                                                                    value="1"
                                                                    {{ $dpStatus ? 'checked' : '' }}>
                                                                <label class="form-check-label small text-muted switch-status-label" for="toggle_{{ $dpPartner }}">
                                                                    {{ $dpStatus ? 'Active' : 'Inactive' }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        @if($dpMerchantId || !empty($dp['merchant_id']))
                                                        <div class="row field-group mt-2 {{ $dpStatus ? '' : 'd-none' }}">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="ubereats_merchant_id">Store Id</label>
                                                                    <input readonly type="text" id="ubereats_merchant_id" name="delivery_partners[{{ $key }}][merchant_id]" class="form-control" placeholder="Uber Eats Store Id" value="{{ $dpMerchantId }}">
                                                                    @error('merchant_id')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif

                                                        <div class="row field-group mt-2 {{ $dpStatus ? '' : 'd-none' }}">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <a href="https://auth.uber.com/oauth/v2/authorize?client_id={{ $uberEatsClientId }}&response_type=code&redirect_uri={{ route('authorize-ubereats') }}?restaurantId={{ $restaurant->id ?? '' }}&scope=eats.pos_provisioning" class="btn btn-dark">{{ ($dpMerchantId || !empty($dp['merchant_id'])) ? 'Change Store' : 'Authorize UberEats' }}</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        @break

                                        @case("grubhub")
                                            <input type="hidden" name="delivery_partners[{{ $key }}][delivery_partner]" value="{{ $dpPartner }}">
                                            <input type="hidden" name="delivery_partners[{{ $key }}][id]" value="{{ $dp['id'] }}">
                                            <div class="col-4">
                                                <label class="mt-5" for=""><b>Grubhub</b></label>
                                                <hr>

                                                <div class="col-12">
                                                    <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                                        <span class="fw-medium">Status</span>
                                                        <div class="form-check form-switch d-flex align-items-center gap-2">
                                                            <input type="checkbox"
                                                                class="form-check-input dp-status-toggle status-switch-toggle"
                                                                name="delivery_partners[{{ $key }}][status]"
                                                                id="toggle_{{ $dpPartner }}"
                                                                value="1"
                                                                {{ $dpStatus ? 'checked' : '' }}>
                                                            <label class="form-check-label small text-muted switch-status-label" for="toggle_{{ $dpPartner }}">
                                                                {{ $dpStatus ? 'Active' : 'Inactive' }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                

                                                    <div class="row field-group mt-2 {{ $dpStatus ? '' : 'd-none' }}">
                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="grubhub_merchant_id">Store Id</label>
                                                                <input type="text" id="grubhub_merchant_id" name="delivery_partners[{{ $key }}][merchant_id]" class="form-control" placeholder="Grubhub Store Id" value="{{ $dpMerchantId }}">
                                                                @error('merchant_id')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="grubhub_issue_date">Issue Date (YYYY-MM-DD)</label>
                                                                <input type="text" id="grubhub_issue_date" name="delivery_partners[{{ $key }}][config][issue_date]" class="form-control" placeholder="Grubhub Issue Date" value="{{ $dpConfig['issue_date'] ?? '' }}">
                                                                @error('grubhub_issue_date')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="grubhub_client_id">Client Id</label>
                                                                <input type="text" id="grubhub_client_id" name="delivery_partners[{{ $key }}][config][client_id]" class="form-control" placeholder="Grubhub Client Id" value="{{ $dpConfig['client_id'] ?? '' }}">
                                                                @error('grubhub_client_id')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="mb-3">
                                                                <label class="form-label" for="grubhub_client_secret">Client Secret</label>
                                                                <input type="text" id="grubhub_client_secret" name="delivery_partners[{{ $key }}][config][client_secret]" class="form-control" placeholder="Grubhub Secret" value="{{ $dpConfig['client_secret'] ?? '' }}">
                                                                @error('grubhub_client_secret')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @break
                                        
                                        @case('doordash')
                                            <input type="hidden" name="delivery_partners[{{ $key }}][delivery_partner]" value="{{ $dpPartner }}">
                                            <input type="hidden" name="delivery_partners[{{ $key }}][id]" value="{{ $dp['id'] }}">
                                            <div class="col-4">
                                                <label class="mt-5" for=""><b>DoorDash</b></label>
                                                <hr>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="border rounded p-3 shadow-sm d-flex justify-content-between align-items-center">
                                                            <span class="fw-medium">Status</span>
                                                            <div class="form-check form-switch d-flex align-items-center gap-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input dp-status-toggle status-switch-toggle"
                                                                    name="delivery_partners[{{ $key }}][status]"
                                                                    id="toggle_{{ $dpPartner }}"
                                                                    value="1"
                                                                    {{ $dpStatus ? 'checked' : '' }}>

                                                                <label class="form-check-label small text-muted switch-status-label" for="toggle_{{ $dpPartner }}">
                                                                    {{ $dpStatus ? 'Active' : 'Inactive' }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="row field-group mt-2 {{ $dpStatus ? '' : 'd-none' }}">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="doordash_merchant_id">Store Id</label>
                                                                    <input type="text" id="doordash_merchant_id" name="delivery_partners[{{ $key }}][merchant_id]" class="form-control" placeholder="Doordash Store Id" value="{{ $dpMerchantId }}">
                                                                    @error('merchant_id')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="developer_id">Developer Id</label>
                                                                    <input type="text" id="developer_id" name="delivery_partners[{{ $key }}][config][developer_id]" class="form-control" placeholder="DoorDash Developer Id" value="{{ $dpConfig['developer_id'] ?? '' }}">
                                                                    @error('developer_id')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="key_id">Key ID</label>
                                                                    <input type="text" id="key_id" name="delivery_partners[{{ $key }}][config][key_id]" class="form-control" placeholder="DoorDash Key ID" value="{{ $dpConfig['key_id'] ?? '' }}">
                                                                    @error('key_id')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="signing_secret">Signing Secret</label>
                                                                    <input type="text" id="signing_secret" name="delivery_partners[{{ $key }}][config][signing_secret]" class="form-control" placeholder="DoorDash signing secret" value="{{ $dpConfig['signing_secret'] ?? '' }}">
                                                                    @error('signing_secret')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @break
                                    @endswitch
                                @endforeach
                            </div>

                            @if (!empty($restaurant) && empty(config('services.cloud.base_url')))
                            @if(!($restaurant->parent_id ?? null))
                            <label class="mt-5" for=""><b>API Authentication</b></label>
                            <hr>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="api_auth_key">API Key</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="api_auth_key" id="api_auth_key" class="form-control" value="{{ old('api_auth_key', $settings['api_auth_key'] ?? '') }}" aria-describedby="button-addon2" placeholder="Save form with empty value new key will be generated.">
                                    <button class="btn btn-sm btn-primary fs-5" type="button" id="reset_api_key" onclick="document.getElementById('api_auth_key').value = ''">Reset</button>
                                </div>
                            </div>
                            @endif

                            <label class="mt-3" for=""><b>Authorize.Net Credentials</b></label>
                            <hr>
                                <div class="mb-3 col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="authorize_login_id">Authorize.Net Login ID</label>
                                    <input type="text" id="authorize_login_id" name="authorize_login_id" class="form-control" placeholder="Authorize.Net Login ID" value="{{ old('authorize_login_id', $settings['authorize_login_id'] ?? '') }}" >
                                    @error('authorize_login_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="authorize_transaction_key">Authorize.Net Transaction Key</label>
                                    <input type="text" id="authorize_transaction_key" name="authorize_transaction_key" class="form-control" placeholder="Authorize.Net Transaction Key" value="{{ old('authorize_transaction_key', $settings['authorize_transaction_key'] ?? '') }}" >
                                    @error('authorize_transaction_key')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3 col-12 col-md-4 col-lg-4">
                                    <label class="form-label" for="authorize_solution_id">Authorize.Net Solution ID</label>
                                    <input type="text" id="authorize_solution_id" name="authorize_solution_id" class="form-control" placeholder="Authorize.Net Solution ID" value="{{ old('authorize_solution_id', $settings['authorize_solution_id'] ?? '') }}" >
                                    @error('authorize_solution_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            @if (!empty($restaurant) && empty(config('services.cloud.base_url')))

                            <label for=""><b>Guacamole URL</b></label>
                            <hr>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="guacamole_url">Guacamole URL</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="guacamole_url" id="guacamole_url" class="form-control" value="{{ old('guacamole_url', $settings['guacamole_url'] ?? '') }}" aria-describedby="button-addon2" placeholder="Cloudflare tunnel url for guacamole">
                                </div>
                            </div>

                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="guacamole_username">Guacamole Username</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="guacamole_username" id="guacamole_username" class="form-control" value="{{ old('guacamole_username', $settings['guacamole_username'] ?? '') }}" aria-describedby="button-addon2" placeholder="Guacamole Username">
                                </div>
                            </div>
             
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="guacamole_password">Guacamole Password</label>
                                <div class="input-group mb-3">
                                    <input type="password" name="guacamole_password" id="guacamole_password" class="form-control" value="{{ old('guacamole_password', $settings['guacamole_password'] ?? '') }}" aria-describedby="button-addon2" placeholder="Guacamole Password">
                                </div>
                            </div>

                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="guacamole_device_ip">Guacamole Device Ip</label>
                                <div class="input-group mb-3">
                                    <input type="text" name="guacamole_device_ip" id="guacamole_device_ip" class="form-control" value="{{ old('guacamole_device_ip', $settings['guacamole_device_ip'] ?? '') }}" aria-describedby="button-addon2" placeholder="Guacamole Device IP">
                                </div>
                            </div>

                            @endif

                            <div class="mb-3 mt-5 col-12 col-md-12 col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Section End-->
@endsection
@section('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script>
        $(document).ready(function () {
            // Multi-branch toggle
            $(document).on("change", "#is_multi_branch", function() {
                if ($(this).is(':checked')){
                    $("#max_branch_limit_container").show();
                    $("#max_branch_limit").attr('required', true);
                } else {
                    $("#max_branch_limit_container").hide();
                    $("#max_branch_limit").attr('required', false);
                }
            });

            // Toggle label text for status switches (Active / Inactive)
            $(document).on("change", ".status-switch-toggle", function() {
                var label = $(this).closest('.form-check').find('.switch-status-label');
                if (label.length) {
                    label.text($(this).is(':checked') ? 'Active' : 'Inactive');
                }
            });

            // Payment methods selection change
            $(document).on("change", "input[name='restaurant_payment_methods[]']", function() {
                if ($("input[name='restaurant_payment_methods[]']:checked").length > 0) {
                    $("#payment_methods_error").addClass('d-none');
                }
            });

            // Delivery partner toggle (visibility and active label)
            $(document).on('change', '.dp-status-toggle', function () {
                var fieldGroup = $(this).closest('.col-12, .col-md-4, .col-4').find('.field-group');
                if ($(this).is(':checked')) {
                    fieldGroup.removeClass('d-none');
                } else {
                    fieldGroup.addClass('d-none');
                }
            });

            // Select2 initialization
            if ($.fn.select2) {
                $('.select2').each(function () {
                    $(this).select2({
                        dropdownParent: $(this).parent()
                    });
                });
            }

            // Auto generate website_name slug from restaurant name if website_name was initially empty
            var initialWebsiteName = "{{ old('website_name', !empty($restaurant) ? $restaurant->website_name : '') }}";
            var isManuallyEdited = initialWebsiteName.length > 0;

            $("#website_name").on("input", function() {
                if ($(this).val().length > 0) {
                    isManuallyEdited = true;
                }
            });

            if (!initialWebsiteName) {
                $("#name").on("keyup change", function() {
                    if (!isManuallyEdited) {
                        var name = $(this).val();
                        var slug = name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
                        $("#website_name").val(slug);
                    }
                });
            }

            // Password matching validation on form input
            $("#password, #c_password").on("keyup change", function() {
                var pass = $("#password").val();
                var cpass = $("#c_password").val();
                if (cpass && pass !== cpass) {
                    $("#c_password_error").removeClass('d-none');
                    if (document.getElementById('c_password')) {
                        document.getElementById('c_password').setCustomValidity("Confirm password must match password.");
                    }
                } else {
                    $("#c_password_error").addClass('d-none');
                    if (document.getElementById('c_password')) {
                        document.getElementById('c_password').setCustomValidity("");
                    }
                }
            });

            // Form Submit Handler
            $("#restaurant_add").on("submit", function(e) {
                var form = this;

                // Check HTML5 validity
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    $(form).addClass('was-validated');
                    return false;
                }

                // Check phone format / length
                var phone = $("#phone").val();
                if (phone && phone.length < 14) {
                    e.preventDefault();
                    $("#phone").focus();
                    if (typeof showAlert === 'function') {
                        showAlert('error', 'Please enter a valid 10-digit phone number.');
                    } else {
                        alert('Please enter a valid 10-digit phone number.');
                    }
                    return false;
                }

                // Check payment methods selection (at least one required)
                if ($("input[name='restaurant_payment_methods[]']").length && $("input[name='restaurant_payment_methods[]']:checked").length === 0) {
                    e.preventDefault();
                    $("#payment_methods_error").removeClass('d-none');
                    $('html, body').animate({
                        scrollTop: $("#payment_methods_error").offset().top - 100
                    }, 200);
                    if (typeof showAlert === 'function') {
                        showAlert('error', 'Please select at least one payment method.');
                    }
                    return false;
                }

                // Check password match if fields exist
                if ($("#password").length && $("#c_password").length) {
                    if ($("#password").val() !== $("#c_password").val()) {
                        e.preventDefault();
                        $("#c_password_error").removeClass('d-none');
                        $("#c_password").focus();
                        return false;
                    }
                }

                // Disable submit button on valid submission to prevent double submission
                $(this).find('button[type="submit"]').prop('disabled', true);
            });
        });
    </script>
@endsection
