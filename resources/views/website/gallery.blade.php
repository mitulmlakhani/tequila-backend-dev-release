@extends('layouts.master')
@section('title', 'Restaurant Settings')
@section('content')

<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12">
                <div class="main-heading">
                    <h4>Restaurant Settings</h4>
                </div>
            </div>
            @include('layouts.flash-msg')
            <div class="col-12">
                <div class="main-content p-3">
                    <form action="{{ route('website.settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="section">
                            <h5>Business Information</h5>
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Business Name</label>
                                <input type="text" class="form-control" id="business_name" name="business_name" 
                                       value="{{ old('business_name', $settings->business_name ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Business Logo</label>
                                <input type="file" class="form-control" id="logo" name="logo">
                                @if(isset($settings->logo))
                                <img width="200" src="{{ asset($settings->logo) }}" alt="Business Logo">
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="banner_subtitle" class="form-label">Banner Sub Title</label>
                                <input type="text" class="form-control" id="banner_subtitle" name="banner_subtitle"
                                       value="{{ old('banner_subtitle', $settings->banner_subtitle ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control" id="banner_title" name="banner_title"
                                       value="{{ old('banner_title', $settings->banner_title ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="banner_desc" class="form-label">Banner Description</label>
                                <input type="text" class="form-control" id="banner_desc" name="banner_desc"
                                       value="{{ old('banner_desc', $settings->banner_desc ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="banner_link" class="form-label">Banner Link</label>
                                <input type="text" class="form-control" id="banner_link" name="banner_link"
                                       value="{{ old('banner_link', $settings->banner_link ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="banner_img" class="form-label">Banner</label>
                                <input type="file" class="form-control" id="banner_img" name="banner_img">
                                @if(isset($settings->banner_img))
                                <img width="500" src="{{ asset($settings->banner_img) }}" alt="Banner">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="locations" class="form-label">Locations</label>
                                <input type="text" class="form-control" id="locations" name="locations"
                                       value="{{ old('locations', $settings->locations ?? '') }}">
                            </div>
                        </div>

                        <div class="section">
                            <h5>Opening Hours</h5>
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                                $times = [];

                                for ($hour = 0; $hour < 24; $hour++) {
                                    for ($minute = 0; $minute < 60; $minute += 30) {
                                        $formattedHour = str_pad($hour, 2, '0', STR_PAD_LEFT);
                                        $formattedMinute = str_pad($minute, 2, '0', STR_PAD_LEFT);
                                        $time = "{$formattedHour}:{$formattedMinute}";
                                        $times[] = $time;
                                    }
                                }
                            @endphp
                            @foreach ($days as $day)
                                @php 
                                    $dayId = strtolower($day);
                                    $openingHours = $settings->opening_hours ?? [];
                                    $isChecked = isset($openingHours[$dayId]['is_open']) ? (bool) $openingHours[$dayId]['is_open'] : true;
                                @endphp
                                <div class="mb-3 row">
                                    <label class="col-md-4 col-form-label">
                                        <input type="checkbox" name="is_open[{{ $dayId }}]" value="1" class="form-check-input day-checkbox" 
                                               id="is_open_{{ $dayId }}" data-day="{{ $dayId }}" {{ $isChecked ? 'checked' : '' }}>
                                        {{ $day }}
                                    </label>

                                    <div class="col-md-4">
                                        <select class="form-control time-picker" name="opening_time[{{ $dayId }}]" id="opening_time_{{ $dayId }}" 
                                                {{ !$isChecked ? 'disabled' : '' }}>
                                            <option value="">Select Opening Time</option>
                                            @foreach ($times as $time)
                                                <option value="{{ $time }}" 
                                                    {{ (isset($settings->opening_hours[$dayId]['opening']) && $settings->opening_hours[$dayId]['opening'] == $time) ? 'selected' : '' }}>
                                                    {{ date('h:i A', strtotime($time)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <select class="form-control time-picker" name="closing_time[{{ $dayId }}]" id="closing_time_{{ $dayId }}" 
                                                {{ !$isChecked ? 'disabled' : '' }}>
                                            <option value="">Select Closing Time</option>
                                            @foreach ($times as $time)
                                                <option value="{{ $time }}" 
                                                    {{ (isset($settings->opening_hours[$dayId]['closing']) && $settings->opening_hours[$dayId]['closing'] == $time) ? 'selected' : '' }}>
                                                    {{ date('h:i A', strtotime($time)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <small class="text-muted closed-text" id="closed_text_{{ $dayId }}" 
                                               style="{{ !$isChecked ? 'display: inline;' : 'display: none;' }}">Closed</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function toggleTimePicker(day) {
            var isChecked = $('#is_open_' + day).is(':checked');
            $('#opening_time_' + day).prop('disabled', !isChecked);
            $('#closing_time_' + day).prop('disabled', !isChecked);
            $('#closed_text_' + day).toggle(!isChecked);
        }

        $('.day-checkbox').each(function () {
            var day = $(this).data('day');
            toggleTimePicker(day);
        });

        $(document).on('change', '.day-checkbox', function () {
            var day = $(this).data('day');
            toggleTimePicker(day);
        });
    });
</script>

@endsection
