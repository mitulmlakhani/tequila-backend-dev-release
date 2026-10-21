@extends('layouts.master')

@section('content')

<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-heading">
          <h4>{{$panel}} Management</h4>
          @can($permission_prefix.'-create')
          <a href="{{ route($base_route.'.create') }}" data-bs-toggle="modal" data-bs-target="#addFloor"
            data-bs-whatever="@mdo">Add {{$panel}}</a>
          @endcan
        </div>
      </div>
      @include('layouts.flash-msg')
      @include('layouts.validation-error-msg')
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-content p-3">
          <table id="floormanagement" class="display nowrap w-100">
            <thead>
              <tr>
                <th scope="rowgroup"></th>
                <th scope="rowgroup">Floor Name </th>
                <th scope="rowgroup">Length (in SqFt)</th>
                <th scope="rowgroup">Width (in SqFt)</th>
                <th scope="rowgroup">Area (in SqFt)</th>
                <th scope="rowgroup">Created by</th>
                <th scope="rowgroup">Status</th>
                <th scope="rowgroup">Action</th>
              </tr>
            </thead>
            <tbody>
            @foreach ($floor as $key => $value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ $value->length }}</td>
                    <td>{{ $value->width }}</td>
                    <td>{{ $value->area }}</td>
                    <td>{{ GetUserById($value->created_by)}}</td>
                    <td> 
                        @if($value->status=='Active')
                        <div class="reserved"><img alt="reserved" src="assets/images/reserved.png" class="me-2"><span>{{ $value->status }}</span></div>
                        @else
                        <div class="pending"><img src="assets/images/pending.png" alt="pending" class="me-2"><span>{{ $value->status}}</span></div>
                        @endif
                    </td>
                   
                    <td>
                        <span class="me-2">
                            @can($permission_prefix.'-edit')
                            <a aria-hidden="true" href="#" id="flooredit" data-bs-toggle="modal"edit-atr="{{$value->id}}" data-bs-target="#editFloor"
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
<div class="modal fade" id="addFloor" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleLabel">Add {{$panel}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::open(array('route' => $base_route.'.store','method'=>'POST')) !!}
      <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_floor">Floor name</label>
                {!! Form::text('name', null, array('placeholder' => 'Floor name','class' => 'form-control','id'=>'add_floor','required')) !!}
                <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_length">Floor Length (in SqFt)</label>
                {!! Form::number('length', null, array('placeholder' => 'Floor Length','class' => 'form-control','id'=>'add_length','required','step'=>'0.01')) !!}
                @error('length')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="add_width">Floor Width (in SqFt)</label>
                {!! Form::number('width', null, array('placeholder' => 'Floor Width','class' => 'form-control','id'=>'add_width','required','step'=>'0.01')) !!}
                @error('width')
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
<div class="modal fade" id="editFloor" tabindex="-1" aria-labelledby="editFloorLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editFloorLabel">Edit {{$panel}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::model($floor, ['method' => 'PATCH','route' => [$base_route.'.update',isset($floor->id)?$floor->id:0]]) !!}
      <div class="modal-body">
        <div class="row">
           <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="edit_floor_name">Floor name</label>
                {!! Form::text('name', null, array('placeholder' => 'Floor name','class' => 'form-control','id'=>'edit_floor_name','required')) !!}
                 {!!Form::hidden('floor_id','',['id'=>'edit_floor_id'])!!}
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="edit_length">Floor Length (in SqFt)</label>
                {!! Form::number('length', null, array('placeholder' => 'Floor Length','class' => 'form-control','id'=>'edit_length','required','step'=>'0.01')) !!}
                @error('length')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 col-12 col-md-6 col-lg-6">
                <label class="form-label" for="edit_width">Floor Width (in SqFt)</label>
                {!! Form::number('width', null, array('placeholder' => 'Floor Width','class' => 'form-control','id'=>'edit_width','required','step'=>'0.01')) !!}
                @error('width')
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
<script type="text/javascript">
   function formatPhone(obj) {
    var numbers = obj.value.replace(/\D/g, ''),
        char = {0:'(',3:') ',6:'-'};
    obj.value = '';
    for (var i = 0; i < numbers.length; i++) {
        obj.value += (char[i]||'') + numbers[i];
    }

}
</script>
@endsection