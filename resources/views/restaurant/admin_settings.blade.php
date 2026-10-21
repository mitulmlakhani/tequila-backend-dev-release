@extends('layouts.master')
@section('title', 'Restaurant Settings')
@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-heading">
                    <h4>Restaurant Settings</h4>
                </div>
            </div>
            @include('layouts.flash-msg')
            <div class="col-12 col-md-12 col-lg-12">
                <div class="main-content p-3">
                    <form action="{{ route('restaurant_settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="section">
                            <h5>Payment Settings</h5>
                            <div class="mb-3 row">
                                <label class="col-md-4 col-form-label" for="cc_fee">Credit Card Fee</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="cc_label" name="cc_label" value="{{ $label }}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" id="" name="" value="{{ $globalFee }}" disabled>
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
@endsection
