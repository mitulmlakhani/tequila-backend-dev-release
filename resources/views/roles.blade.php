@extends('layouts.master')
@section('title')
    Roles Management
@endsection

@section('content')
<div class="wrapper home-section" id="full-width">
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-12 col-md-3 col-lg-3">
                <div class="main-heading">
                    <h4 id="formTitle">Add Role</h4>
                </div>
                @include('layouts.flash-msg')
                <div class="main-content p-3">
                    <form action="{{ route('role-create') }}" method="POST" id="role-form">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label class="form-label" for="name">Role Name</label>
                            <input type="text" placeholder="Name" id="name" name="name" class="form-control" required value="{{ old('name','') }}">
                            @error('name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" id="guard_name" name="guard_name" value="web">
                        <!-- <div class="mb-3">
                            <label class="form-label" for="guard_name">Application Type</label>
                            <select class="form-select" id="guard_name" name="guard_name" required>
                                <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Back-End</option>
                                <option value="api" {{ old('guard_name') == 'api' ? 'selected' : '' }}>Front-End</option>
                            </select>
                            @error('guard_name')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div> -->
                        <div class="mb-3">
                            <label class="form-label" for="status">Status </label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', 1) == 0 ? 'selected' : '' }}>In-active</option>
                            </select>
                            @error('status')
                                <div class="text-danger validation-error">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-dark ms-2" id="resetRoleForm">Reset</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-9 col-lg-9">
                <div class="main-heading">
                    <h4>Roles Management</h4>
                </div>
                <div class="main-content p-3">
                    <table class="table table-striped" id="role_management">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <!-- <th>Application Type</th> -->
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <!-- <td>{{ $role->guard_name == 'web' ? 'Back-End' : 'Front-End' }}</td> -->
                                    <td>
                                        <div class="{{ $role->status ? 'reserved' : 'pending' }}">
                                            <img src="{{ $role->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}" class="me-2">
                                            <span>{{ $role->status ? 'Active' : 'In-active' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#" class="me-2 role-edit" data-id="{{ $role->id }}">
                                            <img src="{{ asset('assets/images/edit.png') }}" alt="Edit">
                                        </a>
                                        <a href="#" class="role-delete" data-url="{{ route('role-delete', ['id' => $role->id]) }}" data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                                            <img src="{{ asset('assets/images/dustbin.png') }}" alt="Delete">
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end">
                        {!! $roles->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteRoleLabel">Delete Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="delete-img">
                    <img src="{{ asset('assets/images/delete.png') }}" alt="delete" class="img-fluid">
                </div>
                <div class="modalcontent">
                    <h4 class="text-center mt-3">Are you Sure?</h4>
                    <p class="text-center mt-3">Do you really want to delete this role?<br>This process cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="delete-role-form" method="POST">
                    @csrf
                    @method('GET')
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const roleDetailUrl = '{{ route('role', ':id') }}';
    const roleUpdateUrl = '{{ route('role-edit', ':id') }}';
    const roleCreateUrl = '{{ route('role-create') }}';

    $(document).on('click', '.role-edit', function () {
        let id = $(this).data('id');
        let url = roleDetailUrl.replace(':id', id);

        $("#role_management").find('tr').each(function (i, tr) {
            this.style.removeProperty('background-color');
        });

        var rolesRow = $(this).closest('tr');

        $.get(url, function (response) {
            if (response.status === 'success') {

                rolesRow.css('background-color', '#ffe');

                const role = response.data;
                $('#formTitle').text('Edit Role');
                $('#name').val(role.name).focus();
                $('#status').val(role.status);
                $('#role-form').attr('action', roleUpdateUrl.replace(':id', id));
                $('#formMethod').val('POST');
            }
        });
    });

    $(document).on('click', '#resetRoleForm', function () {
        $('#formTitle').text('Add Role');
        $('#role-form').attr('action', roleCreateUrl);
        $('#formMethod').val('POST');
        $('#name').val('');
        // $('#guard_name').val('web');
        $('#status').val('1');
    });

    $(document).on('click', '.role-delete', function () {
        let url = $(this).data('url');
        $('#delete-role-form').attr('action', url);
    });

    $('#deleteRoleModal').on('hidden.bs.modal', function () {
        $('#delete-role-form').attr('action', '');
    });
</script>
@endsection