@extends('layouts.master')

@section('content')

<!--Main Section Start-->
<div class="wrapper home-section" id="full-width">
  <div class="container-fluid p-4">
    <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-heading">
          <h4>Permissions</h4>
            @can('permissions-create')
            <a class="d-none" href="{{ route('permissions.create') }}" data-bs-toggle="modal" data-bs-target="#addRole" data-bs-whatever="@mdo">Add Permission</a>
            @endcan
        </div>
      </div>
      @include('layouts.flash-msg')
      @include('layouts.validation-error-msg')
      <div class="col-12 col-md-12 col-lg-12">
        <div class="main-content p-3">
          <table id="permission-management" class="display nowrap w-100">
            <thead>
              <tr>
                <th scope="rowgroup"></th>
                <th scope="rowgroup">Permission name</th>
                <th scope="rowgroup">Display name </th>
                <th scope="rowgroup">Permission Route</th>
                <!-- <th scope="rowgroup">Application Type </th> -->
                <th scope="rowgroup" class="d-none">Action</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($permissions as $permission)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{$permission->group_name }}</td>
                <td>{{ $permission->display_name }}</td>
                <td>{{ $permission->name }}</td>
                <!-- <td>
                     @if($permission->guard_name=='web')
                     Back-End
                     @elseif($permission->guard_name=='api')
                     Front-End
                     @else
                     @endif
                </td> -->
                <td class="d-none">
                  <span class="me-2">
                    @can('permission-edit')
                    <a aria-hidden="true" href="" id="permissionedit" data-bs-toggle="modal"  edit-atr="{{$permission->id}}" data-bs-target="#editpermission"
                        data-bs-whatever="@mdo">
                        <img src="{{asset('assets/images/edit.png')}}" alt="edit">
                    </a>
                    @endcan
                  </span>
                    <span>
                        @can('permission-delete')
                        {!! Form::open(['method' => 'DELETE','route' => ['permissions.destroy', $permission->id],'style'=>'display:inline']) !!}
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
<div class="modal fade" id="addRole" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addRoleLabel">Add Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      {!! Form::open(array('route' => 'permissions.store','method'=>'POST')) !!}

      <div class="modal-body">
        <div class="row">
          <div class="mb-3 col-12 col-md-6 col-lg-6">
            <label class="form-label" for="add_permission_group_name">Permission group name </label>
              {!! Form::text('group_name', null, array('placeholder' => 'Permission group Name','class' =>'form-control','id'=>'add_permission_group_name')) !!}
              @error('group_name')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
          <div class="mb-3 col-12 col-md-6 col-lg-6">
           <label class="form-label" for="add_permission_route">Permission Name</label>
            {!! Form::text('display_name', null, array('placeholder' => 'Permission Name','class' =>'form-control','id'=>'add_permission_route')) !!}
              @error('display_name')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
           <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="add_application_type">Application Type </label>
              <select class="form-select" required id="add_application_type" name="guard_name">
                <option value="">Select Application Type</option>
                <option value="web">Back-End</option>
                <option value="api">Front-End</option>
              </select>
              @error('guard_name')
                <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          </div>
        </div>
      </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
<!--Modal Popup Add end-->
<!--Modal Popup Edit start-->
<div class="modal fade" id="editpermission" tabindex="-1" aria-labelledby="editpermissionLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editpermissionLabel">Edit Permission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       
       
       {!! Form::model($permissions, ['method' => 'PATCH','route' => ['permissions.update', empty($permission) ? 0: $permission->id]]) !!}
      <div class="modal-body">
        <div class="row">
            <div class="mb-3 col-12 col-md-6 col-lg-6">
            <label class="form-label" for="add_permission_group_name">Permission group name </label>
              {!! Form::text('group_name', null, array('placeholder' => 'Permission group Name','class' =>'form-control','id'=>'edit_permission_group_name')) !!}
              @error('group_name')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
              {!!Form::hidden('permission_id','',['id'=>'edit_permission_id'])!!}
          </div>
          <div class="mb-3 col-12 col-md-6 col-lg-6">
           <label class="form-label" for="add_permission_route">Permission Name</label>
            {!! Form::text('display_name', null, array('placeholder' => 'Permission Name','class' =>'form-control','id'=>'edit_permission_route')) !!}
              @error('display_name')
              <div class="alert alert-danger">{{ $message }}</div>
              @enderror
          
          </div>
           <div class="mb-3 col-12 col-md-6 col-lg-6">
              <label class="form-label" for="edit_application_type">Application Type</label>
              <select class="form-select" required id="edit_application_type" name="guard_name">
                <option value="">Select Application Type</option>
                <option value="web">Back-End</option>
                <option value="api">Front-End</option>
              </select>
              @error('guard_name')
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
<script>
  /*edit permisison popup
     */
     $("#permission-management").on('click', '#permissionedit', function(e) {
        // e.preventDefault();
        var id = $(this).attr('edit-atr');
        $('#edit_permission_group_name').val();
        $('#edit_permission_route').val();
        $('#edit_application_type').val();
        $.ajax({
            url: '/permissions/' + id + '/edit',
            type: 'GET',
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id
            },
            async: false,
            success: function(data) {
                // alert(data.resp.id);
                $('#edit_permission_group_name').val(data.resp.group_name);
                $('#edit_permission_route').val(data.resp.name);
                $('#edit_permission_id').val(data.resp.id);
                $('#edit_application_type').val(data.resp.guard_name);


            }
        });
    });
  </script>
  @endsection