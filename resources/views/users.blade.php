<!-- Updated Users Blade File to Match Floor Layout -->
@extends('layouts.master')
@section('title', 'Employees')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">
@endsection

@section('content')
    <style>
        .swipe-dotted {
            background-image: linear-gradient(to right, black 50%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 5px 1px;
            background-repeat: repeat-x;
            border: 1px dashed #ccc;
            color: transparent;
            text-shadow: 0 0 0 #000;
        }
        .swipe-dotted {
            caret-color: transparent;
            user-select: none;
        }

        #select2-role-results {
            max-height: none !important;
            overflow: visible !important;
        }
    </style>
    <div class="wrapper home-section" id="full-width">
        <div class="container-fluid p-4">
            <div class="row">
                <!-- Add/Edit User Form -->
                <div class="col-md-3">
                    <div class="main-heading mb-3">
                        <h4 id="formTitle">Add User</h4>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form id="user-form" action="{{ route('user-create') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="formMethod" value="POST">
                        <input type="hidden" name="_method" id="method-override" value="POST">
                        <!-- Inside user-form -->
                        <div class="mb-2">
                            <label class="form-label" for="role">Select Role</label><br />
                            <select multiple class="form-select w-100" id="role" name="role[]" required>
                                <option value=''>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <div id="role_container" style="margin-top: 50px;"></div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="passcode">Login Number</label>
                            <input type="text" class="form-control" id="passcode" name="passcode" required>
                            <div id="passcodeError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Swipe Card</label>
                            <input type="password" id="card-swipe-input" class="form-control swipe-dotted-removed" autocomplete="off">
                            <input type="hidden" name="card_id" id="card_id" value="{{ old('card_id', $user->card_id ?? '') }}">
                            <div id="card-display" class="mt-2 text-success fw-bold"></div>
                        </div>
                        <div class="mb-3 email-password-section">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="off">
                        </div>
                        <div class="mb-3 email-password-section password-div">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="mobile">Phone</label>
                            <input type="text" class="form-control" id="mobile" name="mobile">
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="ssn_number">SSN Number</label>
                            <input type="text" class="form-control" id="ssn_number" name="ssn_number">
                            <div id="ssnNumberError" class="invalid-feedback"></div>
                        </div>
                        <div class="mb-2">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label" for="join_date">Join Date</label>
                                    <input type="text" class="form-control datepicker" id="join_date" name="join_date">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="terminate_date">Terminate Date</label>
                                    <input type="text" class="form-control datepicker" id="terminate_date" name="terminate_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="image">Image </label>
                            <input type="file" id="image" name="image" class="form-control">
                            @error('image')
                                <div class="validation-error text-danger">{{ $message }}</div>
                            @enderror
                            <img height="75px" src="" id="imagePreview" alt="">
                        </div>
                        <div class="mb-2">
                            <label class="form-label" for="status">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1">Active</option>
                                <option value="0">In-active</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="report_notification_email">Report Notification Email</label>
                            <input type="email" class="form-control" id="report_notification_email" name="report_notification_email" autocomplete="off">
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="reports">Reports</label>
                            <div class="form-check d-flex w-100">
                                <label class="form-check-label" for="sales_report">
                                    <input class="form-check-input" type="checkbox" value="sales" name="reports[sales][name]" id="sales_report">
                                    &nbsp;Sales Summary Report
                                </label>
                                <select class="ms-auto" name="reports[sales][frequency]" id="sales_frequency">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <div class="form-check d-flex w-100 mt-2">
                                <label class="form-check-label" for="batch_out_report">
                                    &nbsp;Batch Out Report
                                    <input class="form-check-input" type="checkbox" value="batch_out" name="reports[batch_out][name]" id="batch_out_report">
                                 </label>
                                <select class="ms-auto" name="reports[batch_out][frequency]" id="batch_out_frequency">
                                    <option value="daily">Daily</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                        </div>  

                        <button type="submit" class="btn btn-primary w-100">Add</button>
                        <button type="button" class="btn btn-secondary w-100 mt-2" id="resetUserForm">Reset</button>
                    </form>
                </div>

                <!-- User List Table -->
                <div class="col-md-9">
                    <div class="main-heading mb-3 d-flex justify-content-between align-items-center">
                        <h4>User Management</h4>
                        <form method="GET" action="{{ route('user-list') }}" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search users..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                    <div class="main-content p-3">
                        <table class="table table-striped" id="users-list">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Contacts</th>
                                    <th>Login Number</th>
                                    <th>User Card</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            {{ $user->name }} 
                                            <br> 
                                            @foreach ($user->getRoleNames() as $v)
                                                <b>{{ $v }}{{ !$loop->last ? ', ' : '' }}</b>
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $user->email }}
                                            <br>
                                            {{ $user->mobile }}
                                        </td>
                                        <td class="passcode" data-passcode="{{ $user->passcode }}">****</td>
                                        <td class="text-center">
                                            @if($user->card_id)
                                                ****
                                            @endif
                                        </td>
                                        <td>
                                            <div class="{{ $user->status ? 'reserved' : 'pending' }}">
                                                <img src="{{ $user->status ? asset('assets/images/reserved.png') : asset('assets/images/pending.png') }}" alt="">
                                                <span>{{ $user->status ? 'Active' : 'In-active' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if (Auth::user()->id != $user->id)
                                                <span class="me-2">
                                                    @can('user-edit')
                                                        <a aria-hidden="true" href="#" id="user-edit"
                                                        data-bs-toggle="modal" data-id="{{ $user->id }}"
                                                        data-bs-target="#user-add-modal" data-bs-whatever="@mdo">
                                                            <img src="{{ asset('assets/images/edit.png') }}" alt="edit">
                                                        </a>
                                                    @endcan
                                                </span>
                                                <span class="me-2">
                                                    @can('user-payroll')
                                                        <a aria-hidden="true" href="#" id="user-payroll" title="Payroll"
                                                        data-bs-toggle="modal" data-id="{{ $user->id }}"
                                                        data-bs-target="#user-payroll-modal" data-bs-whatever="@mdo">
                                                            <img src="{{ asset('assets/images/payroll.png') }}" alt="payroll">
                                                        </a>
                                                    @endcan
                                                </span>
                                                <span class="me-2">
                                                    <a aria-hidden="true" href="{{ route('user-w4-forms', ['id' => $user->id]) }}" title="W4 Forms">
                                                        <img width="24px" src="{{ asset('assets/images/w4-icon.png') }}" alt="W4 Forms">
                                                    </a>
                                                </span>
                                                <span>
                                                    @can('user-delete')
                                                        <a aria-hidden="true" href="#" data-bs-toggle="modal"
                                                        id="deleteUser" data-bs-target="#deleteUserModal"
                                                        data-url="{{ route('user-delete', ['id' => $user->id]) }}">
                                                            <img src="{{ asset('assets/images/dustbin.png') }}" alt="dustbin">
                                                        </a>
                                                    @endcan
                                                </span>

                                                <span class="ms-2">
                                                    @can('user-permissions')
                                                        <a aria-hidden="true" href="{{ route('user-permissions', ['id' => $user->id]) }}">
                                                            <img width="24px" src="{{ asset('assets/images/access-granted.png') }}" alt="Access Granted">
                                                        </a>
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
    @include('users.delete-modal')
    @include('users.payroll-modal')
@endsection
@section('js')
    <link href="{{ asset('assets/css/plugins/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/plugins/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/flatpickr.js') }}"></script>

    <script>
            @if(Session::has('errors'))
                var isValidationError = true;
            @else
                var isValidationError = false;
            @endif

            var userCreateUrl = '{{ route('user-create') }}';
            var userUpdateUrl = '{{ route('user-edit', ':id') }}';
            var userDetailUrl = '{{ route('user', ':id') }}';
            var loadRoleUrl = '{{ route('load-role') }}';
            var checkPasscodeurl = '{{ route('check-passcode') }}';
            var getPayrollUrl = '{{ route('user-get-payroll', ':id') }}';
            var savePayrollUrl = '{{ route('user-save-payroll', ':id') }}';
        </script>
        <script src="{{ asset('assets/js/user.js') }}"></script>
        <script src="{{ asset('assets/js/partial/card_swipe.js') }}"></script>

        <script>
            $("#role").select2({
                width: '100%',
                placeholder: "Select Roles",
                allowClear: true,
                dropdownParent: $("#role_container")
            });

            flatpickr(".datepicker", {
                dateFormat: "Y-m-d"
            });
        </script>
@endsection
