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
{!! Form::model($floor, ['method' => 'PATCH','route' => [$base_route.'.update', $floor->id]]) !!}
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
   
    <div class="col-xs-12 col-sm-12 col-md-12 text-left mt">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</div>
{!! Form::close() !!}

@endsection