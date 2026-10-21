@extends('layouts.user-app')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create New {{$panel}}</h2>
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
            <strong>Name:</strong>
            {!! Form::text('name', null, ['placeholder' => 'Name','class' => 'form-control']) !!}
            @error('name')
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