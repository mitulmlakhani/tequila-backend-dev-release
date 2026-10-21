@extends('layouts.master')
@section('title')
@if (empty($branch))
Add
@else
Edit
@endif Location
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
                            @if (empty($branch))
                                Add
                            @else
                                Edit
                            @endif Location
                        </h4>
                        <a href="{{ URL::previous() }}">Back</a>
                    </div>
                </div>
                @include('layouts.flash-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <form method="POST" id="restaurant_add">
                            @csrf
                        @if(!empty($branch))
                            <input type="hidden" name="is_edit" value="1">
                        @endif
                        <div class="row">
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="name">Restaurant name</label>
                                <input type="text" placeholder="Restaurant Name" id="name" name="name"
                                    class="form-control" value="{{old('name', !empty($restaurant) ? $restaurant->name : '')}}" required disabled>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="branch_name">Location name</label>
                                <input type="text" placeholder="Location Name" id="branch_name" name="branch_name"
                                    class="form-control" value="{{old('branch_name', !empty($branch) ? $branch->branch_name : '')}}" required>
                                @error('branch_name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="phone">Official Phone</label>
                                <input type="text" placeholder="(787) 898-9898" id="phone" name="phone"
                                    class="form-control max10Length" value="{{old('phone', !empty($branch) ? $branch->phone : '')}}" required onkeyup=formatPhone(this)>
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="open_time">Opening Time</label>
                                <input type="text" id="open_time" name="open_time"
                                    class="form-control" value="{{old('open_time', !empty($branch) ? $branch->open_time : '')}}" required autocomplete="off">
                                @error('open_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="close_time">Closing Time</label>
                                <input type="text" id="close_time" name="close_time"
                                    class="form-control" value="{{old('close_time', !empty($branch) ? $branch->close_time : '')}}" required autocomplete="off">
                                @error('close_time')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="first_weekday">First WeekDay</label>
                                <select class="form-select" name="first_weekday">
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "0") selected @endif value="0">Sunday</option>
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "1") selected @endif value="1">Monday</option>
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "2") selected @endif value="2">Tuesday</option>
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "3") selected @endif value="3">Wednesday</option>
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "4") selected @endif value="4">Thursday</option>
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "5") selected @endif value="5">Friday</option>
                                    <option @if(old('first_weekday', $restaurant->first_weekday ?? 0) == "6") selected @endif value="6">Saturday</option>
                                </select>
                                @error('first_weekday')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="dine_in_status"> Dine In Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id=" dine_in_status"
                                        name="dine_in_status" 
                                    @if(old('is_edit'))  
                                        @if(old('dine_in_status') == "on")  
                                            checked 
                                        @endif 
                                    @else 
                                        @if ((isset($branch->dine_in_status) && $branch->dine_in_status == 'Yes') || old('dine_in_status') == "on") 
                                            checked 
                                        @endif
                                    @endif>
                                @error('dine_in_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="take_away_status"> Take Away Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id=" take_away_status"
                                        name="take_away_status" 
                                    @if(old('is_edit'))  
                                        @if(old('take_away_status') == "on")  
                                            checked 
                                        @endif 
                                    @else 
                                        @if ((isset($branch->take_away_status) && $branch->take_away_status == 'Yes') || old('take_away_status') == "on") 
                                            checked 
                                        @endif
                                    @endif>
                                    @error('take_away_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-2 col-lg-2">
                                <label class="form-label" for="delivery_status"> Delivery Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id=" delivery_status"
                                        name="delivery_status" 
                                    @if(old('is_edit'))  
                                        @if(old('delivery_status') == "on")  
                                            checked 
                                        @endif 
                                    @else 
                                        @if ((isset($branch->delivery_status) && $branch->delivery_status == 'Yes') || old('delivery_status') == "on") 
                                            checked 
                                        @endif
                                    @endif>
                                    @error('delivery_status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-12 col-md-6 col-lg-6">
                                <label class="form-label" for="address">Address</label>
                                <textarea type="text" rows="3" placeholder="Restaurant Address" id="address"
                                        name="address"class="form-control" required>{{old('address', !empty($branch) ? $branch->address : '')}}</textarea>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-3 col-lg-3">
                                <label class="form-label" for="timezone"> Timezone*</label>
                                <select class="form-select timezone_select" id="timezone" name="timezone" required>
                                    <option value="">Select Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone->name }}" @if (!empty($branch) && $branch->timezone == $timezone->name) selected @endif>
                                            {{ $timezone->title}} ({{ $timezone->abbreviation }}) {{ $timezone->offset }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('timezone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-3 col-lg-3">
                                <label class="form-label" for="add_status"> Restaurant Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Active" @if (!empty($branch) && $branch->status == "Active") selected @endif>Active
                                    </option>
                                    <option value="In-active" @if (!empty($branch) && !$branch->status = "In-active") selected @endif>
                                        In-active</option>
                                </select>
                            </div>
                            @if (true)
                            <label class="" for=""><b>Credit card charge detail</b></label>
                            <hr>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="cc_fee_label">Credit Card Fee label</label>
                                <input type="text" id="cc_fee_label" name="cc_fee_label" class="form-control" placeholder="Credit Card Fee label" value="{{old('cc_fee_label', !empty($branch) ? $branch->cc_fee_label : '')}}">
                                @error('cc_fee_label')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="cc_fee">Credit Card Fee (in %)</label>
                                <input type="text" id="cc_fee" name="cc_fee" class="form-control percentageInput" placeholder="Credit Card Fee" value="{{old('cc_fee', !empty($branch) ? $branch->cc_fee : '')}}">
                                @error('cc_fee')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <label class="form-label" for="cd_label">Cash Discount label</label>
                                <input type="text" id="cd_label" name="cd_label" class="form-control" placeholder="Cash Discount label" value="{{old('cd_label', !empty($branch) ? $branch->cd_label : '')}}">
                                @error('cd_label')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-12 col-md-4 col-lg-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_cc_fee_applicable" name="is_cc_fee_applicable" 
                                    @if(old('is_edit'))
                                        @if (old('is_cc_fee_applicable')) 
                                            checked 
                                        @endif
                                    @else
                                        @if(!empty($branch) && $branch->is_cc_fee_applicable) 
                                            checked 
                                        @endif
                                    @endif>
                                    <label class="form-check-label" for="is_cc_fee_applicable">
                                      Is Credit Fee Applicable {{old('is_cc_fee_applicable')}}
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_cc_fee_show" name="is_cc_fee_show" 
                                    @if(old('is_edit'))
                                        @if (old('is_cc_fee_show')) 
                                            checked 
                                        @endif
                                    @else
                                        @if(!empty($branch) && $branch->is_cc_fee_show) 
                                            checked 
                                        @endif
                                    @endif>
                                    <label class="form-check-label" for="is_cc_fee_show">
                                      Is Credit Fee Show
                                    </label>
                                </div>
                            </div>
                            @endif

                            @if (empty($branch))
                            <label class="" for=""><b>User detail</b></label>
                            <hr>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="user_name">Name</label>
                                    <input type="text" id="user_name" name="user_name" class="form-control" placeholder="Name" value="{{old('user_name')}}" required>
                                    @error('user_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="email"> Official Email</label>
                                    <input type="email" placeholder="Restaurant Name" id="email" name="email"
                                        class="form-control" value="{{old('email', !empty($branch) ? $branch->email : '')}}" required>
                                    @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Password"
                                        required autocomplete="new-password">
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-12 col-md-6 col-lg-6">
                                    <label class="form-label" for="c_password">Confirm Password</label>
                                    <input type="password" id="c_password" name="c_password"
                                        class="form-control" placeholder="Confirm Password" required>
                                    @error('c_password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            <div class="mb-3 col-12 col-md-12 col-lg-12">
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
        $(document).on("submit", "#restaurant_add", function() {
            $('button[type=submit]').prop('disabled', true);
        });

        var timeConfig = {
            timeFormat: 'hh:mm p',
            interval: 30,
            minTime: '00.00',
            maxTime: '23.59',
            defaultTime: '03:30 PM',
            startTime: '0.00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        }

        @if(!empty(old('open_time')))
            timeConfig.defaultTime = "{{old('open_time')}}";
        @else
            @if(!empty($branch))
                timeConfig.defaultTime = "{{$branch->open_time}}";
            @endif
        @endif
        
        $('#open_time').timepicker(timeConfig);

        var timeConfig = {
            timeFormat: 'hh:mm p',
            interval: 30,
            minTime: '00.00',
            maxTime: '23.59',
            defaultTime: '11:30 PM',
            startTime: '0.00',
            dynamic: false,
            dropdown: true,
            scrollbar: true
        }
        @if(!empty(old('close_time')))
            timeConfig.defaultTime = "{{old('close_time')}}";
        @else
            @if(!empty($branch))
                timeConfig.defaultTime = "{{$branch->close_time}}";
            @endif
        @endif
        $('#close_time').timepicker(timeConfig);
        
    </script>
@endsection
