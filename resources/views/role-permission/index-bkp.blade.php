@extends('layouts.master')

@section('content')

<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-heading">
          <h4>{{$panel}} Management</h4>
        </div>
      </div>
      @include('layouts.flash-msg')
      @include('layouts.validation-error-msg')
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-content p-3">
          {!! Form::open(array('route' => $base_route.'.store','method'=>'POST')) !!}
          <div class="row main-heading">
            <div class="col-12 col-lg-4 col-md-4">
                {!! Form::select('guard_name', $data['applicationtype'],null, array('class' => 'form-select ','required','id'=>'loadrole')) !!}
            </div>
            <div class="col-12 col-lg-3 col-md-3">
                {!! Form::select('roles', $data['role'],null, array('class' => 'form-select ','required','id'=>'loadpermission')) !!}
            </div>
            <div class="col-12 col-lg-3 col-md-3">
              @can($permission_prefix.'-store')
                <button type="submit"  class="btn btn-primary float-end">Save</button>
              @endcan
            </div>
          </div>
          <div class="col-12 col-md-12 col-lg-12 mt-3">
            <table class="table table-bordered ">
              <thead>
                <tr>
                  <th scope="col">Permission Name</th>
                  <th scope="col" colspan="5">Permission Type</th>
                </tr>
              </thead>
              <tbody id="permisison_list">
                @if(isset($data['allpermissions']) && count($data['allpermissions'])>0)
                  @foreach($data['allpermissions'] as $key=>$value)
                    @php
                     $permission_arr=[]; 
                     $permission_arr= explode(',',$value->permisison);

                     @endphp
                    <tr>
                      <th>{{$value->group_name}}

                      </th>
                      <td>
                          <input type="checkbox" class="form-check-input checkAll" id="{{$key+1}}"> All
                          
                      </td>
                      @if(isset($permission_arr) && is_array($permission_arr))
                        @foreach($permission_arr as $permission_key=>$permission_val)
                        
                          <td> 
                            <div class="d-flex">
                               @php
                                  $permission_name= explode('|',$permission_val);
                                
                               @endphp
                              <input type="checkbox" class="form-check-input permisisoncheck{{$permission_name[1]}}" id="restaurantManageall" name="permission[]" value="{{$permission_name[1]}}">
                              <h6 class="mb-0 ms-2">{{$permission_name[0]}}</h6>
                            </div>
                         </td>
                         @endforeach
                     @endif
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
<!--Main Section End-->



@endsection
@section('js')
<script type="text/javascript">
  $(".checkAll").click(function(e){
    var tr= $(e.target).closest('tr');
    $('td input:checkbox',tr).prop('checked',this.checked);
});
</script>
@endsection