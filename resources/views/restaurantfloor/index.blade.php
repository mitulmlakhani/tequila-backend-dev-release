@extends('layouts.master')
@section('css')
  
@endsection
@section('content')

<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-heading">
          <h4>{{$panel}} Management</h4>
          @can($permission_prefix.'-create')
          <a href="{{ route($base_route.'.create') }}" data-bs-toggle="modal" data-bs-target="#addRestaurantFloor"
            data-bs-whatever="@mdo">Add {{$panel}}</a>
          @endcan
        </div>
      </div>
      @include('layouts.flash-msg')
      @include('layouts.validation-error-msg')
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-content p-3">
          <table id="restaurant-floormanagement" class="display nowrap w-100">
            <thead>
              <tr>
                <th scope="rowgroup"></th>
                <th scope="rowgroup">Restaurant Name </th>
                <th scope="rowgroup">Floor Name</th>
                <th scope="rowgroup">Status</th>
                <th scope="rowgroup">Created by</th>
                <th scope="rowgroup">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($restaurantfloor as $key => $value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $value->restaurant_name }}</td>
                    <td>{{ $value->floor_name }}</td>
                    <td>{{ $value->status }}</td>
                    <td>{{ $value->createdBy->name }}</td>
                   
                    <td>
                        <span class="me-2">
                            @can($permission_prefix.'-edit')
                            <a aria-hidden="true" href="#" id="restaurantflooredit" data-bs-toggle="modal"edit-atr="{{$value->id}}" data-bs-target="#editRestaurantFloor"
                          data-bs-whatever="@mdo">
                                <img src="{{asset('assets/images/edit.png')}}" alt="edit">
                            </a>
                            @endcan
                        </span>
                        <span>
                            @can($permission_prefix.'-delete')
                            {!! Form::open(['method' => 'DELETE','route' => [$base_route.'.destroy', $value->id],'style'=>'display:inline']) !!}
                            <a aria-hidden="true" id="" class="confirm-button">
                                <img src="{{asset('assets/images/dustbin.png')}}" alt="dustbin">
                            </a>
                            {!! Form::close() !!}
                            @endcan
                        </span>
                    </td>
                </tr>
              @endforeach

            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</div>
<!--Main Section End-->
<!--Modal Popup Add start-->
<div class="modal fade" id="addRestaurantFloor" tabindex="-1" aria-labelledby="addrestaurantFloorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addrestaurantFloorLabel">Add {{$panel}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::open(array('route' => $base_route.'.store','method'=>'POST')) !!}
      <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_restaurant">Select Restaurant</label>
                {!! Form::select('restaurant_id',$data['restaurant_arr'], null, array('class' => 'form-control','id'=>'add_restaurant','required')) !!}
                @error('restaurant_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                 <label class="form-label" for="add_floor">Select Floor</label>
                {!! Form::select('floor_id',$data['floor_arr'], null, array('class' => 'form-control selectpicker','id'=>'add_floor','required','data-live-search'=>'true','multiple'=>'multiple')) !!}
                @error('floor_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_status">Status</label>
                  {{ Form::select('status',['Active'=>'Active','In-active'=>'In-active'], null, array('class' =>'form-select','id'=>'add_status')) }}
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
               
            </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="Submit" class="btn btn-primary">Add</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!--Modal Popup Add end-->

<!--Modal Popup Edit start-->
<div class="modal fade" id="editRestaurantFloor" tabindex="-1" aria-labelledby="editRestaurantFloorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editRestaurantFloorLabel">Edit {{$panel}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::model($restaurantfloor, ['method' => 'PATCH','route' => [$base_route.'.update',isset($restaurantfloor->id)?$restaurantfloor->id:0]]) !!}
      <div class="modal-body">
        <div class="row">
           <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_restaurant">Select Restaurant</label>
                {!! Form::select('restaurant_id',$data['restaurant_arr'], null, array('class' => 'form-control','id'=>'edit_restaurant','required')) !!}
                {!!Form::hidden('restaurant_floor_id','',['id'=>'edit_restaurant_floor_id'])!!}
                @error('restaurant_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                 <label class="form-label" for="add_floor">Select Floor</label>
                {!! Form::select('floor_id',$data['floor_arr'], null, array('class' => 'form-control','id'=>'edit_floor','required')) !!}
                @error('floor_id')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="edit_status">Status</label>
                  {{ Form::select('status',['Active'=>'Active','In-active'=>'In-active'], null, array('class' =>'form-select','id'=>'edit_status')) }}
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
               
            </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!--Modal Popup Edit end-->



@endsection
@section('js')

@endsection