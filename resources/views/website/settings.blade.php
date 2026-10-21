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
                            @if(!$isBranch)
                            <div class="row">
                                <div class="col-4">
                                    <label for="business_name" class="form-label">Business Name</label>
                                    <input type="text" class="form-control" id="business_name" name="business_name" 
                                        value="{{ old('business_name', $settings->business_name ?? '') }}">
                                </div>
                                <div class="col-4">
                                    <label for="logo" class="form-label">Business Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo">
                                    @if(isset($settings->logo))
                                    <img width="200" src="{{ asset($settings->logo) }}" alt="Business Logo">
                                    @endif
                                </div>
                                <div class="col-4">
                                    <label for="favicon" class="form-label">Favicon</label>
                                    <input type="file" class="form-control" id="favicon" name="favicon">
                                    @if(isset($settings->favicon))
                                    <img width="50" src="{{ asset($settings->favicon) }}" alt="Favicon">
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-4">
                                    <label for="phone_no" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="phone_no" name="phone_no" 
                                        value="{{ old('phone_no', $settings->phone_no ?? '') }}">
                                </div>
                                <div class="col-4">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                        value="{{ old('email', $settings->email ?? '') }}">
                                </div>
                            </div>
                        </div>

                        @if(!$isBranch)
                        <div class="section mt-3">
                            <h5>Banner Information</h5>
                            <div class="row">
                            <div class="col-4 mb-3">
                                <label for="banner_subtitle" class="form-label">Banner Sub Title</label>
                                <input type="text" class="form-control" id="banner_subtitle" name="banner_subtitle"
                                       value="{{ old('banner_subtitle', $settings->banner_subtitle ?? '') }}">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="banner_title" class="form-label">Banner Title</label>
                                <input type="text" class="form-control" id="banner_title" name="banner_title"
                                       value="{{ old('banner_title', $settings->banner_title ?? '') }}">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="banner_desc" class="form-label">Banner Description</label>
                                <input type="text" class="form-control" id="banner_desc" name="banner_desc"
                                       value="{{ old('banner_desc', $settings->banner_desc ?? '') }}">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="banner_link" class="form-label">Banner Link</label>
                                <input type="text" class="form-control" id="banner_link" name="banner_link"
                                       value="{{ old('banner_link', $settings->banner_link ?? '') }}">
                            </div>

                            <div class="col-4 mb-3">
                                <label for="banner_img" class="form-label">Banner</label>
                                <input type="file" class="form-control" id="banner_img" name="banner_img">
                            </div>
                            <div class="col-4 mb-3">
                                @if(isset($settings->banner_img))
                                <img width="300" src="{{ asset($settings->banner_img) }}" alt="Banner">
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- <div class="section">
                            <h5>Locations</h5>
                            <div class="mb-3">
                                <label for="locations" class="form-label">Locations</label>
                                <input type="text" class="form-control" id="locations" name="locations"
                                       value="{{ old('locations', $settings->locations ?? '') }}">
                            </div>
                        </div> --}}

                        <div class="section mt-3">
                            <h5>Social Media Profiles</h5>
                            <div class="row">
                            
                            <!-- Google Review Link -->
                            <div class="mb-3 col-4">
                                <label for="google_review_link" class="form-label">Google Review Link</label>
                                <input type="text" class="form-control" id="google_review_link" name="google_review_link" 
                                    value="{{ old('google_review_link', $settings->google_review_link ?? '') }}" placeholder="Google Review Page Url">
                            </div>
                            
                            <!-- Facebook -->
                            <div class="mb-3 col-4">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="text" class="form-control" id="facebook" name="facebook" 
                                    value="{{ old('facebook', $settings->facebook ?? '') }}" placeholder="https://facebook.com/yourpage">
                            </div>

                            <!-- X (Twitter) -->
                            <div class="mb-3 col-4">
                                <label for="x" class="form-label">X (formerly Twitter)</label>
                                <input type="text" class="form-control" id="x" name="x" 
                                    value="{{ old('x', $settings->x ?? '') }}" placeholder="https://x.com/yourhandle">
                            </div>

                            <!-- Instagram -->
                            <div class="mb-3 col-4">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="text" class="form-control" id="instagram" name="instagram" 
                                    value="{{ old('instagram', $settings->instagram ?? '') }}" placeholder="https://instagram.com/yourhandle">
                            </div>

                            <!-- YouTube -->
                            <div class="mb-3 col-4">
                                <label for="youtube" class="form-label">YouTube</label>
                                <input type="text" class="form-control" id="youtube" name="youtube" 
                                    value="{{ old('youtube', $settings->youtube ?? '') }}" placeholder="https://youtube.com/yourchannel">
                            </div>

                            <!-- WhatsApp -->
                            <div class="mb-3 col-4">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text" class="form-control" id="whatsapp" name="whatsapp" 
                                    value="{{ old('whatsapp', $settings->whatsapp ?? '') }}" placeholder="https://wa.me/yournumber">
                            </div>
                            </div>
                        </div>


                        <div class="section">
                            <h5>Opening Hours</h5>
                            @php
                                $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
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
                            <div class="row">
                            @foreach ($days as $day)
                                @php 
                                    $dayId = strtolower($day);
                                    $openingHours = $settings->opening_hours ?? [];
                                    $isChecked = isset($openingHours[$dayId]['is_open']) ? (bool) $openingHours[$dayId]['is_open'] : true;
                                @endphp
                                <div class="mb-3 row col-4">
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
                        </div>

                        <div class="section">
                            <h5>About Page</h5>
                            <div class="row">
                                <div class="col-6">
                                    <label for="about_title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="about_title" name="about_title" 
                                        value="{{ old('about_title', $settings->about_title ?? '') }}">
                                </div>
                                <div class="col-6">
                                    <label for="about_description" class="form-label">Description</label>
                                    <textarea class="form-control" id="about_description" name="about_description" 
                                        rows="3">{{ old('about_description', $settings->about_description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="section">
                            <h5>Chef Info</h5>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="chef_name" class="form-label">Chef Name</label>
                                    <input type="text" class="form-control" id="chef_name" name="chef_name" 
                                        value="{{ old('chef_name', $settings->chef_name ?? '') }}">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="chef_sub_title" class="form-label">Chef sub Title</label>
                                    <input type="text" class="form-control" id="chef_sub_title" name="chef_sub_title" 
                                        value="{{ old('chef_sub_title', $settings->chef_sub_title ?? '') }}">
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="chef_description" class="form-label">Chef Description</label>
                                    <textarea class="form-control" id="chef_description" name="chef_description" 
                                        rows="3">{{ old('chef_description', $settings->chef_description ?? '') }}</textarea>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="chef_image" class="form-label">Chef Image</label>
                                    <input type="file" class="form-control" id="chef_image" name="chef_image">
                                    @if(isset($settings->chef_image))
                                    <img class="mt-1" width="100" src="{{ asset($settings->chef_image) }}" alt="Chef Image">
                                    @endif
                                </div>
                            </div>
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
