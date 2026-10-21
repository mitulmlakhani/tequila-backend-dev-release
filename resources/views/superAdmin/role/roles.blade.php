@extends('layouts.master')
@section('title')
    Admin Roles
@endsection

@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading ">
                        <h4>{{trans("lang.admRole")}}</h4>
                        @can('role-create')
                            <a href="javascript:void(0)" id="addRoleAction" data-bs-toggle="modal">Add Role</a>
                        @endcan
                    </div>
                </div>
                {{-- @include('layouts.flash-msg')
                @include('layouts.validation-error-msg') --}}
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="expense_type" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="disabled-sorting">#</th>
                                    <th scope="rowgroup">{{trans("lang.name")}}</th>
                                    <!-- <th scope="rowgroup">{{trans("lang.createAt")}}</th> -->
                                    <th scope="rowgroup">{{trans("lang.status")}}</th>
                                    <th scope="rowgroup" class="disabled-sorting">{{trans("lang.action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->name }}</td>
                                       
                                        <!-- <td>{{ GetUserById($role->created_at) }}</td> -->
                                        <td>
                                            <div class="{{ $role->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ asset($role->status ? 'assets/images/reserved.png' : 'assets/images/pending.png') }}"
                                                    class="me-2" alt="{{ $role->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $role->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>

                                            <span class="me-2">
                                                @can('admin-role-edit')
                                                    <a id="{{ $role->id }}" class="edit_role_btn" edit-atr-id="{{ $role->id }}"
                                                        edit-atr-name="{{ $role->name }}" edit-attr-status="{{ $role->status }}" href="javascript:void(0)">
                                                        <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                    </a>
                                                @endcan
                                            </span>
                                            <!-- @if ($role->is_deletable)
                                                <span>
                                                    <a aria-hidden="true" href="#" data-bs-toggle="modal" id="deleteRole"
                                                        data-bs-target="#deleteRoleModal"
                                                        data-url="{{ route('role-delete', ['id' => $role->id]) }}">
                                                        <img src="assets/images/dustbin.png" alt="dustbin">
                                                    </a>
                                                </span>
                                            @endif -->
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
    <div class="modal fade" id="addRole" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Add Role</h5>
                    <button type="button" id="" class="btn-close closeButton"  data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {!! Form::open(['route' => 'admin-role-create', 'method' => 'POST', 'id' => 'roleActionForm', 'name' => 'roleActionForm']) !!}
                
                {!! Form::hidden('record_id', old('record_id'), [
                'id' => 'record_id',
                'required',
                ]) !!}
                <span id="patch"> <!-- update record with form--></span>
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_role_name">Role Name </label>
                            {!! Form::text('name', old('name'), [
                                'placeholder' => 'Role Name',
                                'class' => 'form-control max30Length',
                                'id' => 'add_role_name',
                                'required',
                            ]) !!}
                            
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>                        
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_status">Status</label>
                            {{ Form::select('status', ['' => 'Select Status', 1 => 'Active', 0 => 'In-active'], old('status'), [
                                'class' => 'form-select',
                                'id' => 'add_status',
                            ]) }}
                             @error('status')
                             <div class="text-danger">{{ $message }}</div>
                         @enderror
                        </div>
                       
                    </div>

                </div>
                <div class="modal-footer ">
                    {{-- <button type="button" class="btn btn-secondary" id="cancelButton" data-bs-dismiss="modal">Cancel</button> --}}
                    
                    <button type="button" id="submit_button" class="btn btn-primary submit_button">{{ __(trans('lang.save')) }}</button>
                    
                    <button class="btn btn-primary button_loader" id="button_loader" style="display: none;" disabled>
                        <i class="fa fa-spinner fa-spin"></i> <span class="btnLoaderName">{{ __(trans('lang.save')) }} </span>
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!--Modal Popup Add end-->    
    <!--Delete Modal Popup Start-->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleLabel">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-img">
                        <img src="assets/images/delete.png" alt="delete" class="img-fluid">
                    </div>
                    <div class="modalcontent">
                        <h4 class="text-center mt-3">Are you Sure?</h4>
                        <p class="text-center mt-3">Do you really want to delete ? <br>This process cannot be undone.</p>
                    </div>
                </div>
                <div class="modal-footer ">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-primary" id="deleteRoleBtn">Delete</a>
                </div>
            </div>
        </div>
    </div>
    <!--Delete Modal Popup end-->
@endsection

@section('js')
<script>
    @if(Session::has('errors'))
        var isValidError = true;
    @else
        var isValidError = false;
    @endif    

    var createRoute = "{{ route('admin-role-create') }}";
    var editRoute = "{{ route('admin-role-update', ['id' => ':id']) }}";

    $(document).ready(function() {

        $("#addRoleAction").on('click', function() {
            $("#roleActionForm").attr('action', createRoute);
            $("#add_role_name").val('');
            $("#add_status").val('');
            $("#addRole").modal('show');
        });

        $(".edit_role_btn").on('click', function(e) {
            e.preventDefault();
            var _editRoute = editRoute
            _editRoute = _editRoute.replace(':id', $(this).attr('edit-atr-id'));

            $("#roleActionForm").attr('action', _editRoute);

            $("#add_role_name").val($(this).attr('edit-atr-name'));
            $("#add_status").val($(this).attr('edit-attr-status'));

            $("#addRole").modal('show');
        });
    });
</script>
@endsection
