@extends('layouts.user-app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2> Map {{$panel}}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route($base_route.'.index') }}"> Back</a>
        </div>
    </div>
</div>

{!! Form::open(['route' => $base_route.'.store','method'=>'POST']) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Select Restaurant:</strong>
            {!! Form::select('restaurant_id', $rest_arr,\Session::get('restaurant_id'), ['class' => 'form-control']) !!}
            @error('restaurant_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
           <strong>Select Floor:</strong>
            {!! Form::select('floor_id[]', $floor_arr,null, ['class' => 'form-control','multiple']) !!}
            @error('floor_id')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <div class="col-xs-12 col-sm-12 col-md-12 text-left mt">
        <button type="submit" class="btn btn-primary">Add</button>
    </div>
</div>
{!! Form::close() !!}



@endsection