@extends('layouts.master')
@section('title')
    Users
@endsection
@section('content')
    <!--Main Section Start-->
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-heading">
                        <h4>User Management</h4>
                        @can('user-create')
                            <a href="{{ route('users.create') }}" data-bs-toggle="modal" data-bs-target="#addUser"
                                data-bs-whatever="@mdo">Add User</a>
                        @endcan
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="main-content p-3">
                        <table id="staff-management" class="display nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="rowgroup"></th>
                                    <th scope="rowgroup">Name </th>
                                    <th scope="rowgroup">Email</th>
                                    <th scope="rowgroup">Phone</th>
                                    <th scope="rowgroup">Application Type </th>
                                    <th scope="rowgroup">Role</th>
                                    <!-- <th scope="rowgroup">Created by</th> -->
                                    <th scope="rowgroup">Status</th>

                                    <th scope="rowgroup">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>

                                        <td>{{ $user->email }}</td>

                                        <td>{{ $user->mobile }}</td>
                                        <td>
                                            @if ($user->guard_name == 'web')
                                                Back-End
                                            @elseif($user->guard_name == 'api')
                                                Front-End
                                            @else
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $v)
                                                    {{ $v }}
                                                @endforeach
                                            @endif
                                        </td>
                                        <!-- <td>{{ GetUserById($user->created_by) }}</td> -->
                                        <td>
                                            <div class="{{ $user->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $user->status ? 'assets/images/reserved.png' : 'assets/images/pending.png' }}"
                                                    class="me-2" alt="{{ $user->status ? 'reserved' : 'pending' }}">
                                                <span>{{ $user->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            @if(Auth::user()->id != $user->id)
                                                <span class="me-2">
                                                    @can('user-edit')
                                                        <a aria-hidden="true" href="#" id="staffedit"
                                                            data-bs-toggle="modal"edit-atr="{{ $user->id }}"
                                                            data-bs-target="#editUser" data-bs-whatever="@mdo">
                                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                        </a>
                                                    @endcan
                                                </span>
                                                <span>
                                                    @can('user-delete')
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['users.destroy', $user->id],
                                                            'style' => 'display:inline',
                                                        ]) !!}
                                                        <a aria-hidden="true" id="" class="confirm-button">
                                                            <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                        </a>
                                                        {!! Form::close() !!}
                                                    @endcan
                                                </span>
                                            @endif
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
    <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::open(['route' => 'users.store', 'method' => 'POST']) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_username">Name</label>
                            {!! Form::text('name', null, [
                                'placeholder' => 'User name',
                                'class' => 'form-control',
                                'id' => 'add_username',
                                'required',
                            ]) !!}
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_email">Email</label>
                            {!! Form::text('email', null, [
                                'placeholder' => 'Email',
                                'class' => 'form-control',
                                'id' => 'add_email',
                                'required',
                            ]) !!}
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_phone">Phone</label>
                            {!! Form::text('mobile', null, [
                                'placeholder' => 'Phone',
                                'class' => 'form-control max10Length',
                                'id' => 'add_phone',
                                'required',
                                'onkeyup' => 'formatPhone(this);',
                            ]) !!}
                            @error('mobile')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="loadrole">Application Type </label>
                            {!! Form::select('application_type', $applicationtype, null, [
                                'class' => 'form-select ',
                                'required',
                                'id' => 'loadrole',
                            ]) !!}
                            @error('application_type')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="loadpermission">Select role </label>
                            {!! Form::select('role', $roles, null, ['class' => 'form-select ', 'required', 'id' => 'loadpermission']) !!}
                            @error('roles')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_password">Password</label>
                            {!! Form::password('password', [
                                'placeholder' => 'Password',
                                'class' => 'form-control',
                                'id' => 'add_password',
                                'required',
                            ]) !!}
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_c_password">Confirm Password</label>
                            {!! Form::password('confirm-password', [
                                'placeholder' => 'Confirm Password',
                                'class' => 'form-control',
                                'id' => 'add_c_password',
                                'required',
                            ]) !!}
                            @error('confirm-password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="add_status">Status</label>
                            {{ Form::select('status', [1 => 'Active', 0 => 'In-active'], null, ['class' => 'form-select']) }}
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
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                {!! Form::model($user, [
                    'method' => 'PATCH',
                    'route' => ['users.update', isset($user->id) ? $user->id : 0],
                ]) !!}
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_username">Name</label>
                            {!! Form::text('name', null, ['placeholder' => 'User name', 'class' => 'form-control', 'id' => 'edit_username']) !!}
                            {!! Form::hidden('user_id', '', ['id' => 'edit_user_id']) !!}
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_email">Email</label>
                            {!! Form::text('email', null, ['placeholder' => 'Email', 'class' => 'form-control', 'id' => 'edit_email']) !!}
                            @error('email')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_phone">Phone</label>
                            {!! Form::text('mobile', null, [
                                'placeholder' => 'Phone',
                                'class' => 'form-control max10Length',
                                'id' => 'edit_phone',
                                'onkeyup' => 'formatPhone(this);',
                            ]) !!}
                            @error('mobile')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="loadrole">Application Type </label>
                            {!! Form::select('application_type', $applicationtype, null, [
                                'class' => 'form-select ',
                                'required',
                                'id' => 'editloadrole',
                            ]) !!}
                            @error('application_type')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_select_role">Select role </label>
                            {!! Form::select('role', $roles, null, ['class' => 'form-select ', 'required', 'id' => 'rolelist']) !!}
                            @error('roles')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_password">Password</label>
                            {!! Form::password('password', [
                                'placeholder' => 'Password',
                                'class' => 'form-control',
                                'id' => 'edit_password',
                            ]) !!}
                            @error('password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_c_password">Confirm Password</label>
                            {!! Form::password('confirm-password', [
                                'placeholder' => 'Confirm Password',
                                'class' => 'form-control',
                                'id' => 'edit_c_password',
                            ]) !!}
                            @error('confirm-password')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3 col-12 col-md-6 col-lg-6">
                            <label class="form-label" for="edit_status">Status</label>
                            {{ Form::select('status', [1 => 'Active', 0 => 'In-active'], null, ['class' => 'form-select', 'id' => 'edit_status']) }}
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
        // load role edit popup
        $("#editloadrole").on('change', function() {

            var guardname = $(this).val();
            $("#rolelist").empty();
            if (guardname) {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('load-role') }}",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "guard_name": guardname
                    },
                    success: function(response) {
                        if (response.error == false) {
                            var role = response.resp;
                            /*create role  dropdown */
                            if (!$.isEmptyObject(role)) {
                                $("#rolelist").append("<option value=''>Select Role</option>");
                                $.each(role, function(key, value) {
                                    $('#rolelist').append(
                                        "<option value=" + value.name + ">" + value.name +
                                        "</option>");

                                });
                            }
                        }
                    }
                });
            }
        });

        function formatPhone(obj) {
            var numbers = obj.value.replace(/\D/g, ''),
                char = {
                    0: '(',
                    3: ') ',
                    6: '-'
                };
            obj.value = '';
            for (var i = 0; i < numbers.length; i++) {
                obj.value += (char[i] || '') + numbers[i];
            }

        }
    </script>
@endsection
