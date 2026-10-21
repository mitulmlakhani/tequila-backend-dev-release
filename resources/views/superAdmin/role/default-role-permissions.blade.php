@extends('layouts.master')
@section('title')
    Default Role Permissions
@endsection
@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading">
                        <h4>Default Role Permissions</h4>
                    </div>
                </div>

                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')

                <div class="col-12">
                    <div class="main-content p-3">
                        <form method="POST" action="{{ route('default-role-permission.store') }}" id="default-role-form">
                            @csrf
                            <div id="role-warning" class="alert alert-danger d-none mt-2">
                                Please select a role first.
                            </div>
                            <div class="row main-heading">
                                <div class="col-lg-4">
                                    <select name="roles" class="form-select" id="default-role-select" required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-secondary" id="select-all-default">Select All Permissions</button>
                                </div>
                                <div class="col-lg-3">
                                    <button type="submit" class="btn btn-primary float-end">Save Permissions</button>
                                </div>
                            </div>

                            <hr>
                            <div class="col-12 mt-3">
                                <div class="row p-4">
                                    <div class="col-6">
                                        <h2 class="mb-3">Back Office Permissions</h2>
                                        @foreach ($permissions['web'] as $groupName => $webPermissions)
                                            @php $groupId = strtolower('backoffice_' . str_replace([' ', '/'], '_', $groupName)); @endphp
                                            <div class="mb-4">

                                                <div class="me-3">
                                                    <input type="checkbox" class="form-check-input select-all-group default-group-check" data-group="group_{{ $groupId }}"
                                                        id="group_check_{{ $groupId }}">
                                                    <label for="group_check_{{ $groupId }}" class="ms-1">
                                                        <h4>{{ $groupName }}</h4>
                                                    </label>
                                                </div>

                                                <div class="row">
                                                    @foreach ($webPermissions as $permission)
                                                        <div class="col-lg-4">
                                                            <div class="me-3 mb-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission_checkbox permisisoncheck{{ $permission->id }} group_{{ $groupId }} default-permission-check"
                                                                    name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                                                <label for="perm_{{ $permission->id }}" class="ms-1">
                                                                    {{ $permission->display_name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="col-6">
                                        <h2 class="mb-3">Pos Permissions</h2>
                                        @foreach ($permissions['api'] as $groupName => $posPermissions)
                                            @php $groupId = strtolower('pos_' . str_replace([' ', '/'], '_', $groupName)); @endphp
                                            <div class="mb-4">

                                                <div class="me-3">
                                                    <input type="checkbox" class="form-check-input select-all-group default-group-check" data-group="group_{{ $groupId }}"
                                                        id="group_check_{{ $groupId }}" data-group="{{ $groupId }}">
                                                    <label for="group_check_{{ $groupId }}" class="ms-1">
                                                        <h4>{{ $groupName }}</h4>
                                                    </label>
                                                </div>

                                                <div class="row">
                                                    @foreach ($posPermissions as $permission)
                                                        <div class="col-lg-4">
                                                            <div class="me-3 mb-2">
                                                                <input type="checkbox"
                                                                    class="form-check-input permission_checkbox permisisoncheck{{ $permission->id }} group_{{ $groupId }} default-permission-check"
                                                                    name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}">
                                                                <label for="perm_{{ $permission->id }}" class="ms-1">
                                                                    {{ ucwords(str_replace(['_', '-'], ' ', $permission->display_name)) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    const defaultPermissionUrl = '{{ route('default-role-permission.load') }}';
</script>
<script src="{{ asset('assets/js/default-role-permissions.js') }}"></script>
@endsection
