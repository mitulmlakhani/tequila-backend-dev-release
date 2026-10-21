@extends('layouts.user-app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Edit {{$panel}}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route($base_route.'.index') }}"> Back</a>
        </div>
    </div>
</div>


@include('layouts.validation-error-msg')


{!! Form::model($restaurant, ['method' => 'PATCH','route' => [$base_route.'.update', $restaurant->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Name:</strong>
            {!! Form::text('name', null, ['placeholder' => 'Name','class' => 'form-control']) !!}
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Address:</strong>
            {!! Form::textarea('address', null,['placeholder' => 'Restaurant Address','class' => 'form-control','rows'=>'3']) !!}
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
     <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Phone:</strong>
            {!! Form::number('phone', null,['placeholder' => 'Phone No','class' => 'form-control digitsOnly']) !!}
            @error('phone')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, ['placeholder' => 'Email','class' => 'form-control']) !!}
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Open Time:</strong>
            {!! Form::time('open_time', null,['placeholder' => 'Restaurant Address','class' => 'form-control']) !!}
            @error('open_time')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Close Time:</strong>
            {!! Form::time('close_time', null,['placeholder' => 'Restaurant Address','class' => 'form-control']) !!}
            @error('close_time')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>GST:</strong>
            {!! Form::Number('gst_no', null,['placeholder' => 'GST No','class' => 'form-control']) !!}
            @error('gst_no')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-left mt">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</div>
{!! Form::close() !!}

@endsection