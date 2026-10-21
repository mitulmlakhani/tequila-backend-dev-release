@extends('layouts.master')
@section('title')
    {{ $user->name }} Permissions
@endsection
@section('content')
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <div class="col-12">
                    <div class="main-heading">
                        <h4>{{ $user->name }}'s Permissions</h4>
                    </div>
                </div>
                @include('layouts.flash-msg')
                @include('layouts.validation-error-msg')
                <div class="col-12">
                    <div class="main-content p-3">
                        {!! Form::open(['route' => ['user-permissions-save', $user->id], 'method' => 'POST', 'id' => 'update-permissions']) !!}
                        <div class="row main-heading align-items-end g-2">
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-secondary" id="select-all-permissions">Select All
                                    Permissions</button>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-outline-primary w-100 d-none"
                                    id="apply-default-permissions">
                                    Apply Default Role Permissions
                                </button>
                            </div>
                            <div class="col-lg-2">
                                @canany(['role-create', 'role-edit', 'role-permission-store'])
                                    <button type="submit" class="btn btn-primary float-end">Save</button>
                                @endcan
                            </div>
                        </div>
                        <div class="col-12 mt-3">

                            <div class="row p-4">
                                <div class="col-6">
                                    <h2 class="mb-3">Back Office Permissions</h2>
                                    @foreach ($allpermissions['web'] as $groupName => $permissions)
                                        @php $groupId = strtolower('pos_' . str_replace([' ', '/'], '_', $groupName)); @endphp
                                        <div class="mb-4">

                                            <div class="me-3">
                                                <input type="checkbox" class="form-check-input select-all-group" data-group="group_{{ $groupId }}"
                                                    id="group_check_{{ $groupId }}" data-group="group_{{ $groupId }}">
                                                <label for="group_check_{{ $groupId }}" class="ms-1">
                                                    <h4>{{ $groupName }}</h4>
                                                </label>
                                            </div>

                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-lg-4">
                                                        <div class="me-3 mb-2">
                                                            <input type="checkbox"
                                                                class="form-check-input permission_checkbox permisisoncheck{{ $permission->id }} group_{{ $groupId }}"
                                                                name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                                data-group="group_{{ $groupId }}"
                                                                {{ in_array($permission->id, $disabledPermissions) ? 'disabled' : '' }}
                                                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}
                                                            />
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
                                    @foreach ($allpermissions['api'] as $groupName => $permissions)
                                        @php $groupId = strtolower('pos_' . str_replace([' ', '/'], '_', $groupName)); @endphp
                                        <div class="mb-4">

                                            <div class="me-3">
                                                <input type="checkbox" class="form-check-input select-all-group" data-group="group_{{ $groupId }}"
                                                    id="group_check_{{ $groupId }}">
                                                <label for="group_check_{{ $groupId }}" class="ms-1">
                                                    <h4>{{ $groupName }}</h4>
                                                </label>
                                            </div>

                                            <div class="row">
                                                @foreach ($permissions as $permission)
                                                    <div class="col-lg-4">
                                                        <div class="me-3 mb-2">
                                                            <input type="checkbox"
                                                                class="form-check-input permission_checkbox permisisoncheck{{ $permission->id }} group_{{ $groupId }}"
                                                                name="permission[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                                                {{ in_array($permission->id, $disabledPermissions) ? 'disabled' : '' }}
                                                                {{ in_array($permission->id, $assignedPermissions) ? 'checked' : '' }}
                                                                />
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
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    {{-- <script>
        const loadRolePermissionUrl = '{{ route('load-role-permission') }}';
        const loadDefaultPermissionUrl = '{{ route('load-default-permissions') }}';
    </script>
    <script src="{{ asset('assets/js/role-permission.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            // Select All Permissions
            $('#select-all-permissions').on('click', function() {
                const allChecked = $('.permission_checkbox:enabled').length === $('.permission_checkbox:enabled:checked').length;
                $('.permission_checkbox:enabled').prop('checked', !allChecked);
            });

            // Select All in Group
            $('.select-all-group').on('change', function() {
                const groupIndex = $(this).data('group');
                const isChecked = $(this).is(':checked');
                $(`.${groupIndex}:enabled`).prop('checked', isChecked);
            });

            // Update Group Select All Checkbox
            $('.permission_checkbox').on('change', function() {
                const groupIndex = $(this).attr('class').match(/group-(\d+)/)[1];
                const allInGroup = $(`.${groupIndex}:enabled`).length;
                const checkedInGroup = $(`.${groupIndex}:enabled:checked`).length;
                $(`#group_check_${groupIndex}`).prop('checked', allInGroup === checkedInGroup);
            });

            // Disable select all group checkbox if any permission is disabled
            $('.select-all-group').each(function() {
                const groupIndex = $(this).data('group');
                const anyDisabled = $(`.${groupIndex}:disabled`).length > 0;
                if (anyDisabled) {
                    $(this).prop('disabled', true);
                }
            });

            $('.select-all-group').each(function () {
                const group = $(this).data('group');
                const groupCheckboxes = $('.' + group);
                const allChecked =
                    groupCheckboxes.length ===
                    groupCheckboxes.filter(':checked').length;
                $(this).prop('checked', allChecked);
            });
        });
    </script>
@endsection
