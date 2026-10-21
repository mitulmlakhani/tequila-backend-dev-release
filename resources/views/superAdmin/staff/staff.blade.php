@extends('layouts.web')
@section('title', "Staff Management")
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12 col-md-12 ml-auto mr-auto">
            <div class="card card-lock text-left">
                <div class="card-header ">
                    @if(request()->segment(2) == 'create')
                    Edit User
                    @else
                    {{trans("lang.addStaff")}}
                    @endif
                </div>
                <form role="form" id="formAction" name="formAction"
                    action="{{ (count($getUser) > 0 && $getUser['user_address_id']) ? route('{role}.staff.update', ['role' => $routeUrl, 'staff' => $getUser['id']]) : route('{role}.staff.store', ['role' => $routeUrl]) }}"
                    method="post" autocomplete="on">

                    @csrf
                    @if(request()->segment(4) == 'edit')
                    @method('PATCH')
                    @endif
                    <input type="hidden" name="user_id"
                        value="{{ (count($getUser) > 0 && $getUser['id']) ? $getUser['id'] : ''}}">
                    <input type="hidden" name="user_address_id"
                        value="{{ (count($getUser) > 0 && $getUser['user_address_id']) ? $getUser['user_address_id'] : ''}}">
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.roles")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-user-run"></i>
                                        </span>
                                    </div>
                                    <select data-placeholder="roles" name="role_id" id="role_id"
                                        class="form-control chosen-select" required="true">
                                        <option value="">Select Role</option>
                                        @if($roleEmployee)
                                        @foreach($roleEmployee as $k => $v)
                                        @if ((count($getUser) > 0 && $v->id == $getUser['role_id']) || $v->id ==
                                        old('role_id'))
                                        <option value="{{$v->id}}" selected="selected">{{$v->label}}</option>
                                        @continue
                                        @endif
                                        <option value="{{$v->id}}">{{$v->label}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @if ($errors->has('role_id'))
                                <span class="text-danger">{{ $errors->first('role_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 ml-auto ">
                                <label class="labelling">
                                    {{trans("lang.fname")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label ">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-single-02"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ (count($getUser) > 0 && $getUser['first_name']) ? $getUser['first_name'] : old('first_name') }}"
                                        id="first_name" name="first_name" placeholder="Enter first name."
                                        required="true" minlength="3" maxLength="15" />
                                </div>
                                @if ($errors->has('first_name'))
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                @endif
                            </div>
                            <div class="col-lg-4 col-md-4 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.lname")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-circle-10"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="{{ (count($getUser) > 0 && $getUser['last_name']) ? $getUser['last_name'] : old('last_name') }}"
                                        id="last_name" name="last_name" placeholder="Enter last name." required="true"
                                        minlength="3" maxLength="15">
                                </div>
                                @if ($errors->has('last_name'))
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.dob")}}
                                </label>
                                <div class="input-group form-group ">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-circle-10"></i>
                                        </span>
                                    </div>
                                    <input type="date" class="form-control" id="dob"
                                        value="{{ (count($getUser) > 0 && $getUser['dob']) ? $getUser['dob'] : old('dob') }}"
                                        name="dob" placeholder="Select DOB." max="{{date('Y-m-d')}}" required="true" />
                                </div>
                                @if ($errors->has('dob'))
                                <span class="text-danger">{{ $errors->first('dob') }}</span>
                                @endif
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-lg-4 col-md-4 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.phone")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-mobile"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control only-numeric"
                                        value="{{ (count($getUser) > 0 && $getUser['phone_number']) ? $getUser['phone_number'] : old('phone_number') }}"
                                        id="phone_number" name="phone_number" placeholder="Enter phone number."
                                        maxlength="12" minlength="12" autocomplete="off" required="true">
                                    <span id="phone_error_msg" class="phoneerrormsg" style="color: red; display: none">*
                                        Input digits (0 - 9)</span>
                                </div>
                                @if ($errors->has('phone_number'))
                                <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                                @endif

                            </div>
                            <div class="col-lg-4 col-md-4 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.email")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-email-85"></i>
                                        </span>
                                    </div>
                                    <input type="email"
                                        value="{{ (count($getUser) > 0 && $getUser['email']) ? $getUser['email'] : old('email') }}"
                                        class="form-control" id="email" name="email" placeholder="Enter the email."
                                        required="true" maxLength="50" minlength="5">
                                </div>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="col-lg-4 col-md-4 ml-auto userCredential">
                                <label class="labelling">
                                    {{trans("lang.password")}}
                                    <span class="asterisk asteriskUp">
                                        {{ (count($getUser) > 0 && $getUser['upass']) ? '' : '*' }}
                                    </span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-lock-circle-open"></i>
                                        </span>
                                    </div>
                                    <input value="{{ old('password') }}"
                                        class="form-control {{ (count($getUser) > 0 && $getUser['upass']) ? 'editpass' : 'editpass' }}"
                                        id="{{ (count($getUser) > 0 && $getUser['upass']) ? '' : 'password' }}"
                                        placeholder="Create Password." maxlength="25" minlength="8" autocomplete="off"
                                        @if(count($getUser)> 0 && $getUser['upass']) '' @endif type="password" name="" >
                                </div>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                        </div>
                        <div class="row ">

                            <div class="col-lg-12 col-md-12 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.addressLine")}} 1<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-pin-3"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                        value="{{ (count($getUser) > 0 && $getUser['address_one']) ? $getUser['address_one'] : old('address_one')}}"
                                        class="form-control" id="address_one" name="address_one"
                                        placeholder="Enter address line 1" required="true" maxlength="50" minlength="5"
                                        autocomplete="off">
                                </div>
                                @if ($errors->has('address_one'))
                                <span class="text-danger">{{ $errors->first('address_one') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.addressLine")}} 2
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-pin-3"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                        value="{{ (count($getUser) > 0 && $getUser['address_two']) ? $getUser['address_two'] : old('address_two') }}"
                                        class="form-control" id="address_two" name="address_two"
                                        placeholder="Enter address line 2" maxlength="50" minlength="5"
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.province")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-basket"></i>
                                        </span>
                                    </div>
                                    <select data-placeholder="" id="province_id" name="province_id"
                                        class="form-control chosen-select province_id" required="true">
                                        <option value="" selected="">Select Province</option>
                                        @if($province)
                                        @foreach($province as $kp => $vp)
                                        <option value="{{$vp->id}}"
                                            {{ ((count($getUser) > 0 && $vp->id == $getUser['province_id']) || $vp->id == old('province_id')) ? 'selected' : ''}}>
                                            {{$vp->name}} </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                @if ($errors->has('province_id'))
                                <span class="text-danger">{{ $errors->first('province_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.city")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-shop"></i>
                                        </span>
                                    </div>
                                    <!-- <input type="hidden" name="city" value="120"> -->
                                    <select data-placeholder="City" name="city" id="city"
                                        class="form-control chosen-select select2bs4" required="true">
                                        <option value="" selected="" disabled="">Select City</option>
                                    </select>
                                </div>
                                @if ($errors->has('city'))
                                <span class="text-danger">{{ $errors->first('city') }}</span>
                                @endif
                            </div>

                            <div class="col-lg-6 col-md-6 ml-auto">
                                <label class="labelling">
                                    {{trans("lang.postalCode")}}<span class="asterisk">*</span>
                                </label>
                                <div class="input-group form-group has-label">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="nc-icon nc-badge"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                        value="{{ (count($getUser) > 0 && $getUser['postal_code']) ? $getUser['postal_code'] : old('postal_code') }}"
                                        class="form-control" id="postal_code" name="postal_code" maxlength="7"
                                        placeholder="A1A 1A1" Onkeyup="postalCode(this.value)" required="true">
                                </div>
                                @if ($errors->has('postal_code'))
                                <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer ">
                        @if(request()->segment(3) != 'create')
                        <button type="button" class="btn btn-primary text-centerc" id="submit_button">
                            {{ __('Update') }}
                        </button>

                        <button class="btn btn-primary text-center" id="button_loader" style="display: none;" disabled>
                            <i class="fa fa-spinner fa-spin"></i>{{ __('Update') }}
                        </button>
                        @else
                        <button type="button" class="btn btn-primary text-centerc" id="submit_button">
                            {{ __(trans('lang.submit')) }}
                        </button>

                        <button class="btn btn-primary text-center" id="button_loader" style="display: none;" disabled>
                            <i class="fa fa-spinner fa-spin"></i> {{ __(trans('lang.submit')) }}
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection